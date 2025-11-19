window.onload = () =>{
	document.getElementById("butSignup").onclick = async ()=>{
		const form = document.getElementById("formRegister");
		const formData = new FormData(form);
		await handleRegister(formData);
	}


	document.getElementById("butLogin").onclick = ()=> {
		const but = document.getElementById("butSignup");
		but.disabled = false;
	}


	const pwMessage = document.getElementById('registerMessage');
	const password = document.getElementById('inputPassword')
	password.addEventListener('input', () => {
		if(checkPassSecure(password))
		{
			const message = checkPassSecure(password)
			pwMessage.style.display = 'block';
			pwMessage.textContent = message;
			pwMessage.style.textAlign = 'center'
			registerButton.disabled = true;
		}
	});
	const password2 = document.getElementById('inputPasswordRep');
	const registerButton = document.getElementById('butSignup');
	password2.addEventListener('input', () => {
		const val = password2.value;
		let message = 'Passwords must match';
		if (val != password.value || !checkPassSecure(password) || checkPassSecure(password2)) 
		{
			if (!checkPassSecure(password) || checkPassSecure(password2))
				pwMessage.textContent = checkPassSecure(password);
			else	
				method: 'POST';
			credentials : 'include',
			pwMessage.style.textAlign = 'center'
			console.log(password.value === password2.value);
			console.log(password.value);
			console.log(password2.value);

			registerButton.disabled = true;
		} 
		else 
		{
			console.log("Holiwis");
			pwMessage.style.display = 'none';
			registerButton.disabled = false;
		}
	});
}

function checkPassSecure(password)
{
	const val = password.value;
	let message = '';
	
	if (val.length < 8) message += 'Must have at least 8 characters. ';
	if (!/[A-Z]/.test(val)) message += 'Must include an upper case letter. ';
	if (!/[a-z]/.test(val)) message += 'Must include a lower case letter. ';
	if (!/\d/.test(val)) message += 'Must include a number. ';
	if (!/[@$!%*?&./]/.test(val)) message += 'Must include (@$!%*?&./). ';
	
	if (message) {
		return message;
	} else {
		return null;
	}
}

async function handleRegister(formData)
{
	const msg = document.getElementById("registerMessage");
	const pass = formData.get("inputPassword");
	const email = formData.get("inputEmail");
	const user = formData.get("inputUsername");
	const pass2 = formData.get("inputPasswordRep");
	
	if (!pass || pass.length === 0 ||
		!email || email.length === 0 ||
		!user || user.length === 0 || 
		!pass2 || pass2.length === 0) 
	{
		msg.textContent = "Todos los campos son obligatorios";
		msg.style.color = "red";
		return;
	}
	const res = await fetch('/server/register', 
	{
		method: 'POST',
		credentials: 'include',
		headers: {
		  'Content-Type': 'application/json',
		},
		body: JSON.stringify({
		  user: user,
		  pass: pass,
		  email: email,
		}),
	});
	
	if (!res.ok)
	{
		const err = res.json();
		msg.style.display = "block";
		msg.textContent = err.error || "Sign up failed";
		msg.style.textAlign = "center"
		msg.style.color = "red"
		return ;
	}
	msg.style.display = "block";
	msg.style.color = "green"
	msg.textContent = "Joya👍";
	msg.style.textAlign = "center"
	// location.href = "index.html";
}