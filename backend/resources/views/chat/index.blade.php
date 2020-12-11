<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>

<script>
    var channel = '{{$channel}}';
</script>
<p>Hello, {{$name}}. You channel is {{$channel}}</p>
<div id="messages">

</div>

<form action="/api/chat/send" method="post">
    @csrf
    <input type="hidden" name="author" value="{{$name}}">
    <div>
        <input type="text" name="channel">
    </div>
    <div>
        <textarea name="message" cols="30" rows="10"></textarea>
    </div>
    <button type="submit">Send</button>
</form>

<script>
    var socket = io(':6061');
    socket.on(channel, function (data) {
        console.log(data);
        $('#messages').append(data);
    })
</script>
