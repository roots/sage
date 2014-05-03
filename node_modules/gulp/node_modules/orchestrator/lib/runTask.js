/*jshint node:true */

"use strict";

module.exports = function (task, done) {
	var that = this, finish, cb, isDone = false, start, r, streamError;

	finish = function (err, runMethod) {
		var hrDuration = process.hrtime(start);

		if (isDone && !err) {
			err = new Error('task completion callback called too many times');
		}
		isDone = true;

		var duration = hrDuration[0] + (hrDuration[1] / 1e9); // seconds

		done.call(that, err, {
			duration: duration, // seconds
			hrDuration: hrDuration, // [seconds,nanoseconds]
			runMethod: runMethod
		});
	};

	cb = function (err) {
		finish(err, 'callback');
	};

	try {
		start = process.hrtime();
		r = task(cb);
	} catch (err) {
		return finish(err, 'catch');
	}

	if (r && typeof r.done === 'function') {
		// wait for promise to resolve
		// FRAGILE: ASSUME: Promises/A+, see http://promises-aplus.github.io/promises-spec/
		r.done(function () {
			finish(null, 'promise');
		}, function(err) {
			finish(err, 'promise');
		});

	} else if (r && typeof r.pipe === 'function') {
		// wait for stream to end

		r.on('error', function (err) {
			streamError = err;
		});
		r.on('data', function () {
			streamError = null; // The error wasn't fatal, move along
		});
		r.once('end', function () {
			finish(streamError, 'stream');
		});

	} else if (task.length === 0) {
		// synchronous, function took in args.length parameters, and the callback was extra
		finish(null, 'sync');

	//} else {
		// FRAGILE: ASSUME: callback

	}
};
