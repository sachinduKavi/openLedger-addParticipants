<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <link rel="stylesheet" href="assets/css/styles.css">

     
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

        <title>Login form responsive</title>  

        
        <?php
            session_start();
            $treasury_data = json_decode($_SESSION['treasury_data'], true);

            $error = isset($_GET['error']) ? 'visible': 'hidden';
        ?>

        <style>
            .background {
                position: fixed;
                background: url('<?echo $treasury_data['link']?>');
                background-size: cover;
                background-repeat: no-repeat;
                height: 100vh;
                width: 100vw;
                opacity: 0.2;
            }

            .form__content {
                background-color: whitesmoke;
                padding: 50px;
                width: 60%;
                border-radius: 26px;
            }
        </style>

        <script>

            async function onClickRequest() {
                const userEmail = "Sachindu"
                const userPass = document.getElementById('user-pass').value

                const data = {
                    user_email: userEmail,
                    user_pass: userPass
                };


                // Creating fetch request 
                // const response = await fetch('http://localhost:80/open_ledger/testing.php', options).catch((err) => {
                //     console.log(err)
                // })

                const response = await fetch('http://localhost:80/open_ledger/open_ledgerBack.php', {
                    method: "POST",
                    mode:"cors",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })

                console.log('response', await response.text())
            }

        </script>
        
    </head>

 
    <body>
        <div class='background'></div>
        <div class="l-form">
            <div class="shape1"></div>
            <div class="shape2"></div>

            <div class="form" >
                <div style="display: flex; justify-content: center;">
                    <div style="border-radius: 26px;background-color:red; overflow: hidden">
                    <img src="<?echo $treasury_data['link']?>"  class="form__img">
                    </div>
                
                </div>
                

                <form class="form__content" method='post' action="open_ledgerBack.php">
                    <h1 class="form__title" style="margin-bottom: 0;">Join Treasury</h1>
                    <p>Enter your account email and password to join the treasury.</p>
                    <h3 style="color: #8590AD;"><?echo $treasury_data['treasuryID'];?></h3>
                    <h2 style="margin-bottom: 40px;color: #12192C;"><?echo $treasury_data['treasuryName'];?></h2>
                    

                    <div class="form__div form__div-one">
                        <div class="form__icon">
                            <i class='bx bx-user-circle'></i>
                        </div>

                        <div class="form__div-input">
                            <label for="" class="form__label">User Email</label>
                            <input type="text" class="form__input" id="user-email" name="user_email">
                        </div>
                    </div>

                    <div class="form__div">
                        <div class="form__icon">
                            <i class='bx bx-lock' ></i>
                        </div>

                        <div class="form__div-input">
                            <label for="" class="form__label">Password</label>
                            <input type="password" class="form__input" name="user_pass" id="user-pass">
                            <input type="text" name="treasury_ID" style="visibility: hidden" value="<?echo $treasury_data['treasuryID'];?>"/>
                        </div>
                    </div>
         

                    <button  class="form__button">Request to join</button>
                    <label for="" style="color: red; font-weight:bold; visibility: <?echo $error?>">*Invalid Credentials</label>
                    
                </form>
            </div>

        </div>
        
        <script src="assets/js/main.js"></script>
    </body>
</html>