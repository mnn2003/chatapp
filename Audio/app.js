// app.js
const startButton = document.getElementById('startButton');
const endButton = document.getElementById('endButton');
const localAudio = document.getElementById('localAudio');
const remoteAudio = document.getElementById('remoteAudio');

let localStream;
let remoteStream;
let peerConnection;

const constraints = { audio: true, video: false };

startButton.addEventListener('click', startCall);
endButton.addEventListener('click', endCall);

async function startCall() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia(constraints);
        localAudio.srcObject = localStream;

        const configuration = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] };
        peerConnection = new RTCPeerConnection(configuration);

        localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

        peerConnection.ontrack = handleRemoteTrack;

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        // Send the offer to the other peer (via signaling server, not implemented here)

        // Wait for the answer from the other peer (via signaling server, not implemented here)

    } catch (error) {
        console.error('Error starting the call:', error);
    }
}

function handleRemoteTrack(event) {
    remoteStream = event.streams[0];
    remoteAudio.srcObject = remoteStream;
}

async function endCall() {
    // Close the peer connection and release resources
    peerConnection.close();
    localStream.getTracks().forEach(track => track.stop());

    // Reset UI
    startButton.disabled = false;
    endButton.disabled = true;
}
