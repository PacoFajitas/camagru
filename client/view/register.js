window.onload = () =>{
	document.getElementById("butSignup").onclick = ()=>{
		const formData = document.getElementById("formRegister")
		//Llamar a la api para hacer el login y verificar
		location.href = "main.html";
	}
	document.getElementById("butLogin").onclick = ()=> {
		location.href = "login.html"
	}
}