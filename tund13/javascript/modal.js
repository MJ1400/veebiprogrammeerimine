let modal;
let modalImg;
let captionText;
let photoDir = "../picuploadw600h400/";
let photoId;

window.onload = function(){
	modal = document.getElementById("myModal");
	modalImg = document.getElementById("modalImg");
	captionText = document.getElementById("caption");
	let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
	let thumbCount = allThumbs.length;
	for(let i = 0; i < thumbCount; i ++){
		allThumbs[i].addEventListener("click", openModal);
	}
	document.getElementById("close").addEventListener("click", closeModal);
	document.getElementById("storeRating").addEventListener("click", storeRating);
	document.getElementById("edit").addEventListener("click", editModal);
}

function storeRating(){
	let rating = 0;
	for(let i = 1; i < 6; i ++){
		if(document.getElementById("rate" + i).checked){
			rating = document.getElementById("rate" + i).value;
		}
	}

	if(rating > 0){
		//AJAX
		let webRequest = new XMLHttpRequest();
		webRequest.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				//console.log("Edu: " + this.responseText);
				document.getElementById("avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
				console.log("score" + photoId);
				document.getElementById("score" + photoId).innerHTML = "Hinne: " + this.responseText;
			}
		}
		webRequest.open("GET", "savePhotoRating.php?rating=" + rating + "&photoid=" + photoId, true);
		webRequest.send();
	}
}


function openModal(e){
	//console.log(e);
	modalImg.src = photoDir + e.target.dataset.fn;
	photoId = e.target.dataset.id;
	captionText.innerHTML = "<p>" + e.target.alt + "</p>";
	modal.style.display = "block";
}

function closeModal(){
	modal.style.display = "none";
}

function editModal(){
	modal.style.display = "none";
}
