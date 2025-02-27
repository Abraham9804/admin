const form = document.querySelector("#formLogin")

form.addEventListener("submit", handleSubmit)

function handleSubmit(e){
    e.preventDefault()
    const email = document.querySelector("#inputEmail").value 
    const password = document.querySelector("#inputPassword").value
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if(password.trim() === "" || email.trim() === ""){
        showAlert("Error","La contraseña y el correo no pueden estar vacios", "error")
        return
    }else if(!emailRegex.test(email)){
        showAlert("Error","El email no es valido", "error")
        return
    }
    sendForm(email, password)
    

}

async function sendForm(email, password){
    try{
        const formdata = new FormData()
        formdata.append('email',email)
        formdata.append('password',password)

        const fetchForm = await fetch('login/auth.php',{
            method: "POST",
            body: formdata
        })
        if(!fetchForm.ok){
            throw new Error("Error sending data")
        }
        
        const response = await fetchForm.json()
        if(response.success){
            window.location.href = "index.php"
        }else{
            showAlert("Error", 'Incorrect credentials' , "error")
        }
    }
    catch(err){
        showAlert("Error",err.message, "error")
    }
}

function showAlert(title,text, icon){
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Comfirm"
      });
}