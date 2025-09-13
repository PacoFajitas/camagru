window.onload = () =>{
	document.getElementById("butLogin").onclick = ()=>{
		const formData = document.getElementById("formLogin")
		//Llamar a la api para hacer el login y verificar
		location.href = "main.html";
	}
	document.getElementById("butSignup").onclick = ()=> {
		location.href = "register.html"
	}
	document.getElementById("butGithub").onclick = ()=> {
		location.href = "github.com/pacofajitas"
	}
}