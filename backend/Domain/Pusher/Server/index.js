var io = require('socket.io')(6061),
    Redis = require('ioredis'),
    redis = new Redis({
        port: 6379, // Redis port
        host: "redis", // Redis host
        family: 4, // 4 (IPv4) or 6 (IPv6)
        password: "redis",
        db: 0,
    });

redis.psubscribe('*', function (error, count) {
    //
});

redis.on('pmessage', function (pattern, channel, message) {
    console.log(channel);
    console.log(message);
    message = JSON.parse(message);
    io.emit(message.channel, message.message)
    console.log('Sent message to channel: '+message.channel)
});

console.log('Listen on 6061...');
