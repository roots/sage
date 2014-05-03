var client = require("./index").createClient();

client.hmset("test hash", "key 1", "val 1", "key 2", "val 2");
