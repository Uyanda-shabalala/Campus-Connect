<?php
session_start();
include '../../app/Includes/components/header.php';
include_once '../../app/Includes/components/validation.php'

    ?>

<head>
    <title>Messages - Campus Connect</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<main class="container">
    <h2>Messages</h2>

    <section class="chat" id="chat">


    </section>

    <form class="chat-form" name="chat-form" onsubmit="return false">
        <input id="message" type="text" placeholder="Type your message..." name="message">
        <button type="button" id="sendbtn">Send</button>
    </form>


    <script>
        //create a socket that connets instantly to the websocket server when the messages page loads 
        const socket = new WebSocket("ws://localhost:8080");

        socket.onopen = () => {
            console.log("chat connection has started");
            socket.send(JSON.stringify({
                type: "register",
                userId: "<?php echo $_SESSION['user_Id']; ?>"
            }));

        };

        socket.onmessage = (event) => {//so when the message event takes place we need to gather all information relating to that message into a single variable that way we can access specific things we want from the message data 
            console.log("Recipient Received Data:", event.data);
            //by turning it into json we are standardizing it so that it can be 
            const data = JSON.parse(event.data);//json.parse is a method to conver js string array data to json format
            //once this data has been saved as json and can be broken up without manual looping and stuff to search through the message data -
            //i now need to create the actual chat tags and return it into back into the chat by manipulating The Dom .

            //all this is still taking place in the front end , ill send the data to the server once ive returned it to the user or rather at the same time (real time )asynchronus code -

            //creating the chat look and logic 

            const chatsection = document.getElementById("chat");
            const text = document.createElement("p");
            text.textContent = `${data.sender}: ${data.message}`;//who is saying what in the chat 
            chatsection.appendChild(text);
        };

        //"logic" behind sending the messages  and saving them
        document.getElementById('sendbtn').onclick = () => {
            const message = document.getElementById('message').value;

            if (message.trim() === "") return;

            socket.send(JSON.stringify({
                sender: "<?php echo $_SESSION["user_Id"]; ?>",
                receiver: "<?php echo $_SESSION["receiver_Id"]; ?>",
                message: message
            }));


            fetch("../../app/Includes/save_message.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: message })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        console.log("Message saved to database");
                    } else {
                        console.error("Failed to save:", data.message);
                    }
                });


            document.getElementById("message").value = "";
        };


    </script>

</main>
<?php include '../../app/Includes/components/footer.php'; ?>