document.addEventListener('DOMContentLoaded', function()
{
	let request_name = document.getElementById('qa_request_name');
	console.log(request_name);
	request_name.addEventListener('input',(e)=>{
		let btn = document.getElementById('submitBtn');
		if(e.target.value != "")
		{
			btn.removeAttribute('disabled');
		}
		else
		{
			btn.setAttribute('disabled',true);
		}
	});
	
    let comTextareas = document.getElementsByTagName('textarea');
    let comTextAreasArray =Array.from(comTextareas);

    comTextAreasArray.forEach((textbox)=>{
        textbox.addEventListener('blur',(e)=>{
            let data = {
                comment: e.target.value,
                id: e.target.id
            };
            fetch("/wp-json/qa_plugin/update", {
                method: "POST",
                body: JSON.stringify(data),
				 headers: {
				  'Content-Type': 'application/json'
				},
            }).then(res => {
               let alert = document.getElementById('alert');
				alert.style.display = "block";
				alert.style.opacity = '1';
				setTimeout(function() {
  					alert.style.opacity = '0';
				}, 1500);
            });
        })
    });
	
	let checkBoxes = document.getElementsByName('check');
	let checkBoxesArray = Array.from(checkBoxes);
	
	checkBoxesArray.forEach((check)=>{
		check.addEventListener('click',(e)=>{
			 let params = {
				 checked: e.target.checked,
				 id: e.target.id,
				 name: e.target.value
			 };
		 		fetch("/wp-json/qa_plugin/update_checked", {
					method: "POST",
					body: JSON.stringify(params),
					 headers: {
				 	 'Content-Type': 'application/json'
				},
            })
		      .then(response => response.json())
			.then(data => {
					let history = document.getElementById('history'+ params['id']);
					history.innerHTML = JSON.parse(data);
			})  
        })
	})
	
	let btn = document.getElementById('submitBtn');
	btn.addEventListener('click',async () => {
		let qa_request_name = document.getElementById('qa_request_name').value;
		let qa_request_desc = document.getElementById('qa_request_desc').value;
		
		let params = {
			title : qa_request_name,
			description : qa_request_desc
		}

			await fetch("/wp-json/qa_plugin/insert", {
						method: "POST",
						body: JSON.stringify(params),
						 headers: {
						 'Content-Type': 'application/json'
					},
			 })
			 .then(response => response.json())
			 .then(data => {
							location.reload();
			});
		})
	
	let btnClear = document.getElementById('clearBtn');
	btnClear.addEventListener('click',async () => {
		 await fetch("/wp-json/qa_plugin/delete")
			 .then(res => {
			 if(res.ok) {
				 let tableRows = document.getElementsByName('tableRow');
				 let tableRowsArray = Array.from(tableRows);
				 
				 tableRowsArray.forEach((tableRow) => {
					 tableRow.querySelector('.qa-checkbox').checked=false;
					 tableRow.querySelector('.qa-comment').value="";
					 tableRow.querySelector('.qa-history').innerText="";
				 });
			 }});
		
	})
	}
,false);

