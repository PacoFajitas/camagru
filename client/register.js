window.onload = () =>{
	document.getElementById("butSignup").onclick = async ()=>{
		const form = document.getElementById("formRegister")
		const formData = new formData(form);
		await handleRegister(formData);
	}
	document.getElementById("butLogin").onclick = ()=> {
		location.href = "login.html"
	}
	const pwMessage = document.getElementById('registerMessage');
	const password = document.getElementById('inputPassword')
	password.addEventListener('input', () => {
		const val = password.value;
		let message = '';
		
		if (val.length < 8) message += 'Must have at least 8 characters. ';
		if (!/[A-Z]/.test(val)) message += 'Must include an upper case letter. ';
		if (!/[a-z]/.test(val)) message += 'Must include a lower case letter. ';
		if (!/\d/.test(val)) message += 'Must include a number. ';
		if (!/[@$!%*?&.]/.test(val)) message += 'Must include (@$!%*?&.). ';
		
		if (message) {
			pwMessage.style.display = 'block';
			pwMessage.textContent = message;
			pwMessage.style.textAlign = 'center'
		} else {
			pwMessage.style.display = 'none';
		}
	});
	const password2 = document.getElementById('inputPasswordRep');
	const registerButton = document.getElementById('butSignup');
	password2.addEventListener('input', () => {
		const val = password2.value;
		let message = 'Passwords must match';
		if (val != password.value) {
			pwMessage.style.display = 'block';
			pwMessage.textContent = message;
			pwMessage.style.textAlign = 'center'
			registerButton.disabled = true;

		} else {
			pwMessage.style.display = 'none';
			registerButton.disabled = false;
		}
	});
}

async function handleRegister(formData)
{
	const msg = document.getElementById("registerMessage");
	$pass = formData.getElementById("inputPassword");
	$email = formData.getElementById("inputEmail");
	$user = formData.getElementById("inputUser");
	const res = await fetch('/server/register', 
	{
		method: 'POST',
		credentials: 'include',
		headers: {
		  'Content-Type': 'application/json',
		},
		body: JSON.stringify({
		  user: $user,
		  pass: $pass,
		  email: $email,
		}),
	});
	
	if (!res.ok)
	{
		const err = await res.json();
		msg.style.display = "block";
		msg.textContent = err.error || "Sign up failed";
		msg.style.textAlign = "center"
		return ;
	}
	await res.json();
	location.href = "main.html";
}