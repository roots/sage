/* eslint-disable no-console */
'use strict'; // eslint-disable-line

const spawn = require('child_process').spawn;

const mergeWithConcat = require('./util/mergeWithConcat');
const config = require('./config');

module.exports = class {
  constructor(watcher, options) {
    this.watcher = watcher;
    this.options = mergeWithConcat({
      ssh: `ssh -o PasswordAuthentication=no -i ${process.env.HOME}/.vagrant.d/insecure_private_key`,
      flags: 'a',
      watch: [],
    }, options);
  }
  watch() {
    this.watcher.watch(this.options.watch).on('change', this.rsync.bind(this));
  }
  command(source) {
    const destination = `${this.options.username}@${this.options.hostname}:${this.options.destination}/${source}`;
    const args = [];
    args.push(`-${this.options.flags}`);
    args.push(`-e "${this.options.ssh}"`);
    args.push(source);
    args.push(destination);
    return `rsync ${args.join(' ')}`;
  }
  rsync(source) {
    const command = this.command(source);
    const start = new Date();
    const childProcess = spawn('/bin/sh', ['-c', command], {
      cwd: config.paths.root,
      stdio: 'pipe',
      env: process.env,
    });

    childProcess.stderr.on('data', data => console.error(`rsync: ${data}`));
    childProcess.on('close', (code) => {
      if (code !== 0) console.error(`rsync exited with code ${code}`);
      console.info('rsynced: %s [%dms]', source, new Date() - start);
      this.watcher.reload();
    });
  }
};
