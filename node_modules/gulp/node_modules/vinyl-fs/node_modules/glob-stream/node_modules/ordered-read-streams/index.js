var Readable = require('stream').Readable;
var util = require('util');

function OrderedStreams(streams, options) {
  streams = streams || [];
  options = options || {};

  if (!Array.isArray(streams)) {
    streams = [streams];
  }

  options.objectMode = true;

  Readable.call(this, options);

  var self = this;

  if (streams.length === 0) {
    this.push(null); // no streams, close
  } else {
    // stream data buffer
    this._buff = {};
    this._totalStreams = streams.length;
    this._openedStreams = streams.length;
    streams.forEach(function (s, i) {
      if (!s.readable) {
        throw new Error('All input streams must be readable');
      }

      if (!self._buff[i]) {
        self._buff[i] = [];
      }

      s.on('data', function (data) {
        if (i === 0) {
          // from first stream we simply push data
          self.push(data);
        } else {
          self._buff[i].push(data); // store in buffer for future
        }
      });
      s.on('end', function () {
        if (!--self._openedStreams) {
          // no more opened streams
          // flush buffered data (if any) before end
          for (var j = 0; j < self._totalStreams; j++) {
            while (self._buff[j].length) {
              self.push(self._buff[j].shift());
            }
          }
          self.push(null);
        }
      });
      s.on('error', function (e) {
        self.emit('error', e);
      });
    });
  }
}

util.inherits(OrderedStreams, Readable);

OrderedStreams.prototype._read = function () {};

module.exports = OrderedStreams;
