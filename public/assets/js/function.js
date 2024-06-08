/**
 * Affichage de la page d'authentification de l'application
 * @param null
 * @return null
 */
export const displayLoginApp = () => {
  $("#header").hide();
  $("#sidebar").hide();
  $("#main-section").hide();
  $("#connexion-section").show();
  $("#inscription-section").show();
  $("footer").show();
};

/**
 * Affichage de la page principale de l'application
 * @param null
 * @return null
 */
export const displayMainApp = () => {
  $("#header").show();
  $("#sidebar").show();
  $("#main-section").show();
  $("#connexion-section").hide();
  $("#inscription-section").hide();
  $("footer").hide();
};

/**
 * Récupération de la liste des conversations dans la base de données
 * @param connectedUserId - identifiant de l'utilisateur connecté
 * @param baseUrl - url de base du site
 * @return null
 */
export const loadConversation = (connectedUserId, baseUrl) => {
  if (baseUrl) {
    $.ajax({
      type: "POST",
      url: `${baseUrl}lists-conversation`,
      success: function(response) {
        conversationList(
          response,
          connectedUserId,
          "Il y aucune conversation à afficher.",
          baseUrl
        );
      },
      error: function(response) {
        console.error(response.responseText);
      }
    });
  }
};

/**
 * Création d'une conversation
 * @param connectedUserId - identifiant de l'utilisateur connecté
 * @param baseUrl - url de base du site
 * @return null
 */
export const createConversation = (connectedUserId, baseUrl) => {
  $("#btn-new-message").click(() => {
    $("#titre").val("");
    $("#interlocuteur").val("");
    $("#new-message-content").val("Bonjour !");
  });

  $.ajax({
    type: "GET",
    url: `${baseUrl}users`,
    success: function(response) {
      for (const interlocuteurs of response) {
        const { idUser, pseudo } = interlocuteurs;
        if (connectedUserId === idUser) { continue; } 
        $("#interlocuteur").append(`<option value="${idUser}">${pseudo}</option>`);
      }
    },
    error: function(response) {
      console.error(response.responseText);
    }
  });

  $("#form-new-message").submit(e => {
    e.preventDefault();

    const conversation = {
      title: $("#titre").val(),
      interlocuteur: $("#interlocuteur").val(),
      content: $("#new-message-content").val()
    };

    if (
      conversation?.title &&
      conversation?.interlocuteur &&
      conversation?.content
    ) {
      $.ajax({
        type: "POST",
        url: `${baseUrl}new-conversation`,
        data: conversation,
        success: function(response) {
          console.log(response.message);

          $("#new-message-modal").modal("hide");
          $("#sidebar-content").html("");
          loadConversation(connectedUserId, baseUrl);
        },
        error: function(response) {
          console.error(response.responseText);
        }
      });
    }
  });
};

/**
 * Recherche d'une conversation par son titre
 * @param connectedUserId - identifiant de l'utilisateur connecté
 * @param title - bout de titre d'une conversation à rechercher
 * @param baseUrl - url de base du site
 * @return null
 */
export const findConversation = (connectedUserId, title, baseUrl) => {
  if (title) {
    $("#sidebar-content").html("");
    $.ajax({
      type: "POST",
      url: `${baseUrl}find-conversation`,
      data: { search: title },
      success: function(response) {
        conversationList(response, connectedUserId, "La conversation n'existe pas.", baseUrl);
      },
      error: function(response) {
        console.error(response.responseText);
      }
    });
  } else {
    $("#sidebar-content").html("");
    loadConversation(connectedUserId, baseUrl);
  }
};

/**
 * Test d'identification si un utilisateur est déjà connecté
 * @param connectedUserId - identifiant de l'utilisateur connecté
 * @param baseUrl - url de base du site
 * @return null
 */
export const testConnexion = (connectedUserId, baseUrl) => {
  $.ajax({
    type: "GET",
    url: `${baseUrl}test-connexion`,
    success: function(response) {
      const { online, userId } = response;
      if (online) {
        connectedUserId = userId;
        displayMainApp();
        loadConversation(connectedUserId, baseUrl);
        createConversation(connectedUserId, baseUrl);
        findConversation();
      } else {
        displayLoginApp();
      }
    },
    error: function(response) {
      console.error(response.responseText);
    }
  });
};

//! Les fonction non exporté

/**
 * Ajout la liste des conversations de la base données
 * @param response - donnée à traiter
 * @param connectedUserId - identifiant de l'utilisateur connecté
 * @param msg - message à afficher
 * @param baseUrl - url de base du site
 * @return null
 */
const conversationList = (response, connectedUserId, msg, baseUrl) => {
  for (const conversation of response) {
    let { idConversation, title, contact, createdAt } = conversation;
    if (!contact?.pseudo) {
      continue;
    } else {
       $("#sidebar-content").prepend(`
            <tr class="conversation" id="conversation-${idConversation}">
                <td>
                    <h4>${contact?.pseudo.slice(0, 1).toUpperCase().concat(contact?.pseudo.slice(1, contact?.pseudo.length + 1))}</h4>
                    <p>${title}  <span class="float-right">${timeAgo(createdAt)}</span></p>
                </td>
            </tr>
       `);

      $(`#conversation-${idConversation}`).click(() => loadMessage(idConversation, contact?.idUser, connectedUserId, baseUrl));
    }
  }

  if (!$(".conversation").length) {
    $("#sidebar-content").append(`
      <tr class="lead text-center text-light">
        <td>${msg}</td>
      </tr>`
    );
  }
};

/**
 * Envoi de message dans une conversation
 * @param content - contenue du message
 * @param senderId - identifiant de l'utilisateur connecté
 * @param receiverId - identifiant de l'interlocuteur
 * @param idConversation - identifiant de la conversation
 * @param baseUrl - url de base du site
 * @return null
 */
const sendMessage = (content, senderId,receiverId, idConversation, baseUrl) => {
  const message = {
    content: content,
    senderId: senderId,
    receiverId: receiverId,
    conversationId: idConversation
  };

  if (message.content) {
    $.ajax({
      type: "POST",
      url: `${baseUrl}send-message`,
      data: message,
      success: function(response) {
        $(`#sending-${idConversation}`).before(`
          <p class="lead message ml-auto text-right">
            <span class="btn btn-dark radius text-left">${response.content}</span>
          </p>
        `)
      },
      error: function(response) {
        console.error(response.responseText);
      }
    });
  }
};

/**
 * Récupération de la liste de message d'une conversation
 * @param idConversation - identifiant de la conversation
 * @param idReceiver - identifiant de l'interlocuteur
 * @param connectedUserId - identifiant de l'utilisateur connecté
 * @param baseUrl - url de base du site
 * @return null
 */
const loadMessage = (idConversation, idReceiver, connectedUserId, baseUrl) => {
  const mainMessage = $("#main-message");
  mainMessage.html("");

  $.ajax({
    type: "POST",
    url: `${baseUrl}lists-message`,
    data: { idConversation },
    success: function(response) {
      for (const messages of response) {
        const { content, senderId } = messages;
        const align = idReceiver !== senderId ? "ml-auto text-right" : "mr-auto text-left";
        const btnColor = idReceiver !== senderId ? "btn-dark" : "btn-info";

        mainMessage.append(`
            <p class="lead message ${align}">
                <span class="btn ${btnColor} radius text-left">${content}</span>
            </p>
        `);
      }

      mainMessage.append(`
        <form class="form-inline py-2" id="sending-${idConversation}">
            <div class="form-group w-75 mr-3">
                <input type="text" class="form-control btn-radius w-100" id="content-${idConversation}" placeholder="Écrire message ...">
            </div>
            <button type="submit" class="btn btn-dark btn-radius ml-3" style="width: 20%;">Envoyer</button>
        </form>
      `);

      $.ajax({
        type: "POST",
        data: {idConversation},
        url: `${baseUrl}conversation-by-id`,
        success: function(response) {
            mainMessage.prepend(`
            <div class="text-center">
                <h2>${response.title}</h2>
            </div>
          `);
        },
        error: function(response) {
          console.error(response.responseText);
        }
      });

      $(`#sending-${idConversation}`).submit(e => {
        e.preventDefault();
        const content = $(`#content-${idConversation}`).val();
        sendMessage(
          content,
          connectedUserId,
          idReceiver,
          idConversation,
          baseUrl
        );
        $(`#content-${idConversation}`).val("");
      });
    },
    error: function(response) {
      console.error(response);
    }
  });
};

const timeAgo = (time) => {
  const currentTime    = new Date();
  const lastPost       = new Date(time);
  const timeDifference = currentTime - lastPost;
  const msPerMinute    = 1000 * 60;
  const minutesAgo     = Math.floor(timeDifference / msPerMinute);
  const hoursAgo       = Math.floor(minutesAgo / 60);
  const daysAgo        = Math.floor(hoursAgo / 24);
  const weeksAgo       = Math.floor(daysAgo / 7);
  const monthsAgo      = Math.floor(weeksAgo / 4);
  
  if (minutesAgo < 60) {
    return (minutesAgo < 1) 
      ? `À l'instant.` 
      : (minutesAgo < 2) 
        ? `Il y a ${minutesAgo} minute`
        : `Il y a ${minutesAgo} minutes`;
  }
  
  if (hoursAgo < 24) {
    return (hoursAgo < 2) ? `Il y a ${hoursAgo} heure` : `Il y a ${hoursAgo} heures`;
  }
  
  if (daysAgo < 7) {
    return (daysAgo < 2) ? `Il y a ${daysAgo} jour` : `Il y a ${daysAgo} jours`;
  }

  if (weeksAgo < 4) {
    return (weeksAgo < 2) ? `Il y a ${daysAgo} semaine` : `Il y a ${daysAgo} semaines`;
  }

  return `Il y a ${monthsAgo} mois`;
};