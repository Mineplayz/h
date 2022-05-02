//var SimplePeer = require('simple-peer');
var peer;
function Host()
{
    peer = new SimplePeer({
        initiator: true,
        config: { iceServers: [
            { urls: 'stun:stun.l.google.com:19302'},
            {
                urls: "turn:numb.viagenie.ca",
                username: "webrtc@live.com",
                credential: "muazkh"
            }
        ]}
    });
    peer.on('connect', () => {
        console.log("Peer Connected");
    });
    testing();
}
function Client()
{
    peer = new SimplePeer({
        initiator: false,
        config: { iceServers: [
            { urls: 'stun:stun.l.google.com:19302'},
            {
                urls: "turn:numb.viagenie.ca",
                username: "webrtc@live.com",
                credential: "muazkh"
            }
        ]}
    });
    testing();
}
function testing()
{
    peer.on('error', err => console.log('error', err));

      peer.on('signal', data => {
        console.log('SIGNAL', JSON.stringify(data));
      });
  /*    document.querySelector('form').addEventListener('submit', ev => {
        ev.preventDefault()
        peer.signal(JSON.parse(document.querySelector('#incoming').value))
      })*/

      peer.on('connect', () => {
        console.log('CONNECT')
        p.send('whatever' + Math.random())
      });

      peer.on('data', data => {
        console.log('data: ' + data)
      });
}
function setinfo(val)
{
    peer.signal(JSON.parse(val))
}