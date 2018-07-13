var environment = (window.location.hostname == "localhost") ? "http://localhost:8000" : "https://streamtobe.com";
var environmentSocket = (window.location.hostname == "localhost") ? "http://localhost:3001" : "https://io.streamtobe.com";

var options = {
    year: 'numeric', 
    month: 'numeric', 
    day: 'numeric', 
    hour: 'numeric', 
    minute:'numeric'
};
var currentDate = new Date();

/* ENVOI */

// Connexion à socket.io
var socket = io.connect(environmentSocket);
var token = getUrlParameter('token');

// Affichage de la liste des contacts mutuels
socket.emit('bringFriends', token);
socket.on('bringFriends', function(data) {
    
    $("#profile #profile-img").prop("src", environment+"/storage/"+data.user_avatar);
    $("#profile p").html(data.user_pseudo);

    var text = "";
    $.each(data.contactList, function(index, value){
        text += "<li class='contact   ";
                text += (index==0) ? "active'" : "'";
                text += "data-pseudo='"+value.pseudo+"'>";
            text += "<div class='wrap'>";
                text += "<img src='"+environment+"/storage/"+value.avatar+"' alt='' />";
                text += "<p class='name'>"+value.pseudo+"</p>";
                text += "<p class='preview'>Lorem Ipsum</p>";
            text += "</div>";
        text += "</li>";
    });

    if(text==""){
        text += "<li class='contact'>";
            text += "<div class='wrap'>";
                text += "<p class='name'>Aucun contact de joignable</p>";
            text += "</div>";
        text += "</li>";
    }
    $('#contacts ul').html(text);
});

//Changement de conversation
$('#contacts').on("click", "li.contact", function(){
    var friend = $(this).data('pseudo');
    socket.emit('join', friend);
});
socket.on('join', function(data) {
    //Informations
    var infos = data.infos;
    $("#profile #profile-img").prop("src", environment+"/storage/"+infos.user_avatar);
    $("#profile p").html(infos.user_pseudo);

    $(".contact-profile img").prop("src", environment+"/storage/"+infos.friend_avatar);
    $(".contact-profile p").html(infos.friend_pseudo)

    //Conversations
    var conversations = data.conversations;
    var text = "";

    $.each(conversations, function(index, element){
        var message = "<li ";
        message += (element.user_exped == "me") ? "class='replies'" : "class='sent'";
        message += ">";
            message += "<img src='"+environment+"/storage/";
                message += (element.user_exped == "me") ? infos.user_avatar : infos.friend_avatar;
            message += "' alt='' />";
            message += "<p>"+element.message+"</p>";
        message += "</li>";
        text += message;
    });
    $(".messages ul").html(text);
    $(".messages").animate({ scrollTop: $(".messages")[0].scrollHeight }, 100);
});


// Quand on envoie un message, on l'insère dans la page
function newMessage() {
	var message = $(".message-input input").val();
	if($.trim(message) == '')
		return false;
	
    var content = "<li class='replies'>";
            content += "<img src='"+$("#profile-img").prop("src")+"' alt='' />";
            content += "<p>"+message+"</p>";
    content += "</li>";
    $(".messages ul").append(content);
    socket.emit('message', message); // Publie le message
    
    $('.message-input input').val(null);
	$('.contact.active .preview').html(message);
	$(".messages").animate({ scrollTop: $(document).height() }, "fast");
};

$('.submit').click(function() { newMessage(); });

$(window).on('keydown', function(e) {
  if (e.which == 13) { 
    newMessage();
    return false;
  }
});


// Quand on recoit un message, on l'insère dans la page
socket.on('message', function(message) {
    console.log(message);
    if(message){
        var content = "<li class='sent'>";
            content += "<img src='"+$("#contacts li.contact.active img").prop("src")+"' alt='' />";
            content += "<p>"+message+"</p>";
        content += "</li>";
        $(".messages ul").append(content);
    }
    $(".messages").animate({ scrollTop: $(document).height() }, "fast");
});

//Recherche et affichage d'un membre parmi la liste de contact disponible
$("#search_contact").keyup(function(){
    if($(this).val() == "")
        $("#contacts li").show();
    else{
        $("#contacts li").hide();
        $("#contacts li[data-pseudo*='"+$(this).val()+"']").show();
    }
});

/* FONCTIONS */

//Recupère les paramètres fournies dans l'URL
function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};