#include "bind.hpp"
#include "ast.hpp"
#include "context.hpp"
#include "eval.hpp"
#include <map>
#include <iostream>
#include <sstream>
#include "to_string.hpp"

namespace Sass {
  using namespace std;

  void bind(string callee, Parameters* ps, Arguments* as, Context& ctx, Env* env, Eval* eval)
  {
    map<string, Parameter*> param_map;

    // Set up a map to ensure named arguments refer to actual parameters. Also
    // eval each default value left-to-right, wrt env, populating env as we go.
    for (size_t i = 0, L = ps->length(); i < L; ++i) {
      Parameter*  p = (*ps)[i];
      param_map[p->name()] = p;
      // if (p->default_value()) {
      //   env->current_frame()[p->name()] = p->default_value()->perform(eval->with(env));
      // }
    }

    // plug in all args; if we have leftover params, deal with it later
    size_t ip = 0, LP = ps->length();
    size_t ia = 0, LA = as->length();
    while (ia < LA) {
      if (ip >= LP) {
        stringstream msg;
        msg << callee << " only takes " << LP << " arguments; "
            << "given " << LA;
        error(msg.str(), as->path(), as->position());
      }
      Parameter* p = (*ps)[ip];
      Argument*  a = (*as)[ia];

      // If the current parameter is the rest parameter, process and break the loop
      if (p->is_rest_parameter()) {
        if (a->is_rest_argument()) {
          // rest param and rest arg -- just add one to the other
          if (env->current_frame_has(p->name())) {
            *static_cast<List*>(env->current_frame()[p->name()])
            += static_cast<List*>(a->value());
          }
          else {
            env->current_frame()[p->name()] = a->value();
          }
        } else {

          // copy all remaining arguments into the rest parameter, preserving names
          List* arglist = new (ctx.mem) List(p->path(),
                                             p->position(),
                                             0,
                                             List::COMMA,
                                             true);
          env->current_frame()[p->name()] = arglist;
          while (ia < LA) {
            a = (*as)[ia];
            (*arglist) << new (ctx.mem) Argument(a->path(),
                                                 a->position(),
                                                 a->value(),
                                                 a->name(),
                                                 false);
            ++ia;
          }
        }
        ++ip;
        break;
      }

      // If the current argument is the rest argument, extract a value for processing
      else if (a->is_rest_argument()) {
        // normal param and rest arg
        List* arglist = static_cast<List*>(a->value());
        // empty rest arg - treat all args as default values
        if (!arglist->length()) {
          break;
        }
        // otherwise move one of the rest args into the param, converting to argument if necessary
        if (arglist->is_arglist()) {
          a = static_cast<Argument*>((*arglist)[0]);
        } else {
          Expression* a_to_convert = (*arglist)[0];
          a = new (ctx.mem) Argument(a_to_convert->path(), a_to_convert->position(), a_to_convert, "", false);
        }
        arglist->elements().erase(arglist->elements().begin());
        if (!arglist->length() || (!arglist->is_arglist() && ip + 1 == LP)) {
          ++ia;
        }
      } else {
        ++ia;
      }

      if (a->name().empty()) {
        if (env->current_frame_has(p->name())) {
          stringstream msg;
          msg << "parameter " << p->name()
          << " provided more than once in call to " << callee;
          error(msg.str(), a->path(), a->position());
        }
        // ordinal arg -- bind it to the next param
        env->current_frame()[p->name()] = a->value();
        ++ip;
      }
      else {
        // named arg -- bind it to the appropriately named param
        if (!param_map.count(a->name())) {
          stringstream msg;
          msg << callee << " has no parameter named " << a->name();
          error(msg.str(), a->path(), a->position());
        }
        if (param_map[a->name()]->is_rest_parameter()) {
          stringstream msg;
          msg << "argument " << a->name() << " of " << callee
              << "cannot be used as named argument";
          error(msg.str(), a->path(), a->position());
        }
        if (env->current_frame_has(a->name())) {
          stringstream msg;
          msg << "parameter " << p->name()
              << "provided more than once in call to " << callee;
          error(msg.str(), a->path(), a->position());
        }
        env->current_frame()[a->name()] = a->value();
      }
    }

    // If we make it here, we're out of args but may have leftover params.
    // That's only okay if they have default values, or were already bound by
    // named arguments, or if it's a single rest-param.
    for (size_t i = ip; i < LP; ++i) {
      To_String to_string;
      Parameter* leftover = (*ps)[i];
      // cerr << "env for default params:" << endl;
      // env->print();
      // cerr << "********" << endl;
      if (!env->current_frame_has(leftover->name())) {
        if (leftover->is_rest_parameter()) {
          env->current_frame()[leftover->name()] = new (ctx.mem) List(leftover->path(),
                                                                      leftover->position(),
                                                                      0,
                                                                      List::COMMA,
                                                                      true);
        }
        else if (leftover->default_value()) {
          // make sure to eval the default value in the env that we've been populating
          Env* old_env = eval->env;
          Backtrace* old_bt = eval->backtrace;
          Expression* dv = leftover->default_value()->perform(eval->with(env, eval->backtrace));
          eval->env = old_env;
          eval->backtrace = old_bt;
          // dv->perform(&to_string);
          env->current_frame()[leftover->name()] = dv;
        }
        else {
          // param is unbound and has no default value -- error
          stringstream msg;
          msg << "required parameter " << leftover->name()
              << " is missing in call to " << callee;
          error(msg.str(), as->path(), as->position());
        }
      }
    }

    return;
  }


}
