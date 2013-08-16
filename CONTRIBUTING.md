# Contributing to Roots Theme

## Reporting issues

**We only accept issues that are bug reports or feature requests.** Bugs must
 be isolated and reproducible problems that we can fix within Roots. Please
 read the following guidelines before [opening any issues](https://github.com/retlehs/roots/issues):

1. **Use the GitHub issue search.** Check to see if the issue has already been
reported. If it has been, please comment on the existing issue. An existing
issue may also already have a fix available.

2. **Isolate the problem to Roots.** Make sure that the code in the Roots
repository is _definitely_ responsible for the issue. Switch to a core WordPress
theme (such as Twenty Twelve) to confirm problems before reporting an issue.
Make sure you have reproduced the bug with all plugins disabled. Any issues
related to HTML5 Boilerplate or Bootstrap should be reported to their respected
repositories and follow their contributing guidelines.

3. **Do not use GitHub issues for questions or support.** If you have a question
or support request, please post on the [Google Group](http://groups.google.com/group/roots-theme).

Please try to be as detailed as possible in your report. What steps will
reproduce the issue? What would you expect to be the outcome? All these details
will help people to assess and fix any potential bugs. A good bug report
shouldn't leave people needing to chase you up to get further information.

**[File a bug report](https://github.com/retlehs/roots/issues)**


## Pull requests

Good pull requests — patches, improvements, new features — are a fantastic
help. They should remain focused in scope and avoid containing unrelated
commits.

If your contribution involves a significant amount of work or substantial
changes to any part of the project, please open an issue to discuss it first.

Please follow this process; it's the best way to get your work included in the
project:

1. [Fork](https://help.github.com/articles/fork-a-repo) the project.

2. Clone your fork (`git clone
   https://github.com/<your-username>/roots.git`).

3. Add an `upstream` remote (`git remote add upstream
   https://github.com/retlehs/roots.git`).

4. Get the latest changes from upstream (`git pull upstream
   master`).

5. Create a new topic branch to contain your feature, change, or fix (`git
   checkout -b <topic-branch-name>`).

6. Make sure that your changes adhere to the current coding conventions used
   throughout the project - indentation, accurate comments, etc. Please update
   any documentation that is relevant to the change you are making.

7. Commit your changes in logical chunks; use git's [interactive
   rebase](https://help.github.com/articles/interactive-rebase) feature to tidy
   up your commits before making them public. Please adhere to these [git commit
   message
   guidelines](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html)
   or your pull request is unlikely be merged.

8. Locally merge (or rebase) the upstream branch into your topic branch.

9. Push your topic branch up to your fork (`git push origin
   <topic-branch-name>`).

10. [Open a Pull Request](https://help.github.com/articles/using-pull-requests) with a
    clear title and description.
