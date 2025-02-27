const form = document.querySelector("#formRegister")
form.addEventListener("submit", handleSubmit)

function handleSubmit(e){
    e.preventDefault()
    const name = document.querySelector("#inputFirstName").value
    const lastName = document.querySelector("#inputLastName").value
    const email = document.querySelector("#inputEmail").value
    const password = document.querySelector("#inputPassword").value
    const confirmPassword = document.querySelector("#inputPasswordConfirm").value
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(name.trim() === "" || lastName.trim() === "" || email.trim() === "" || password.trim() === "" || confirmPassword.trim() === ""){
        showAlert("Error","Fill in all the fields", "error")
        return
    }else if(password !== confirmPassword){
        showAlert("Error","Las contraseñas deben ser iguales", "error")
        return
    } else if(!emailRegex.test(email)){
        showAlert("Error","El correo no tiene un formato valido", "error")
    }else if(password.length < 6){
        showAlert("Error","La contraseña debe tener al menos 6 caracteres", "error")
    }else {
        sendForm(name, lastName, email, password)
    }
}

async function sendForm(name, lastName, email, password){
    try{
        const formdata = new FormData()
        formdata.append("name",name)
        formdata.append("lastName",lastName)
        formdata.append("email",email)
        formdata.append("password",password)
        const fetchForm = await fetch("login/register.php",{
            method: "post",
            body: formdata
        })
        if(!fetchForm.ok){
            throw new Error("Error sending data")
        }
        const response = await fetchForm.json()
        if(response.success){
            showAlert("success",response.message, "success")
        }else{
            showAlert("Error","Ocurrio un error en el registro", "error")
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