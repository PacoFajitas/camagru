window.onload = () =>{
	document.getElementById("butLogin").onclick = async ()=>{
		const form = document.getElementById("formLogin");
		const formData = new FormData(form);
		await handleLogin(formData);
		// location.href = "main.html";
	}
	document.getElementById("butSignup").onclick = ()=> {
		location.href = "register.html"
	}
	document.getElementById("butGithub").onclick = ()=> {
		window.open("https://github.com/pacofajitas", "_blank");
	}
}

async function handleLogin(formData)
{
	const msg = document.getElementById("loginMessage");
	try {
		const res = await fetch('/server/auth', {
		  method: 'POST',
		  credentials: 'include',
		  headers: {
			'Content-Type': 'application/json',
		  },
		  body: JSON.stringify({
			user: formData.get('inputUsername'),
			pass: formData.get('inputPassword'),
		  }),
		});
		
		if (!res.ok) {
			const err = await res.json();
			msg.style.display = "block";
			msg.textContent = err.error || "Login failed";
			msg.style.textAlign = "center"
			return;
		  }
	  
		  // login correcto
		  const result = await res.json();
		  msg.style.color = "green";
		  msg.style.display = "block";
		  msg.textContent = "Login successful ✅";
		  console.log("Usuario ID:", result);
	  
		} 
	catch (err) 
	{
			msg.style.display = "block";
			msg.style.textAlign = "center"
			msg.textContent = "Network error ❌";
	}
}
