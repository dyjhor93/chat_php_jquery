<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Chat - Customer Module</title>
    <link type="text/css" rel="stylesheet" href="style.css" />
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <?php
    session_start();

    function loginForm()
    {
        echo '
    <div id="loginform">
    <form action="index.php" method="post">
        <p>Please enter your name to continue:</p>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" />
        <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
    </div>
    ';
    }

    if (isset($_POST['enter'])) {
        if ($_POST['name'] != "") {
            $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
            $fp = fopen("log.html", 'a');
            fwrite($fp, "<div class='msgln'>(".date("g:i A").")<i> User " . $_SESSION['name'] . " has enter the chat session.</i><br></div>\n");
            fclose($fp);
        } else {
            echo '<span class="error">Please type in a name</span>';
        }
    }
    ?>

    <?php
    if (isset($_GET['logout'])) {

        //Simple exit message
        $fp = fopen("log.html", 'a');
        fwrite($fp, "<div class='msgln'>(".date("g:i A").")<i> User " . $_SESSION['name'] . " has left the chat session.</i><br></div>");
        fclose($fp);

        session_destroy();
        header("Location: index.php"); //Redirect the user
    }
    if (!isset($_SESSION['name'])) {
        loginForm();
    } else {
    ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
                
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
                
                
                <p class=""><a id="" target="_blank" href="https://api.whatsapp.com/send?phone=573045595064&text=Hola%20Jhordy%2C%20">Whatsapp to Developer</a></p>
            </div>
            <div id="chatbox"><?php
                                if (file_exists("log.html") && filesize("log.html") > 0) {
                                    $handle = fopen("log.html", "r");
                                    $contents = fread($handle, filesize("log.html"));
                                    fclose($handle);

                                    echo $contents;
                                }
                                ?></div>


                
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" size="63" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>

        <script type="text/javascript">
            // jQuery Document
            $(document).ready(function() {
                //If user wants to end session
                $("#exit").click(function() {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                        window.location = 'index.php?logout=true';
                    }
                });
                $("#submitmsg").click(function() {
                    var clientmsg = $("#usermsg").val();
                    $("#usermsg").val('');
                    $.post("post.php", {
                        text: clientmsg
                    });
                    $("#usermsg").attr("value", "");
                    return false;
                });

                function loadLog() {
                    var lastScroll = $("#chatbox").scrollTop(); //Scroll height before the request
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function(html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div	

                            //Auto-scroll
                            var newscrollTop = lastScroll + 20; //Scroll height after the request
                            $("#chatbox").animate({
                                    scrollTop: newscrollTop
                                }, 'slow');
                        },
                    });
                }

                setInterval(loadLog, 2500);
            });
        </script>

    <?php
    }
    ?>
</body>

</html>