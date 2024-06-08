import {
  testConnexion,
  displayLoginApp,
  displayMainApp,
  loadConversation,
  createConversation,
  findConversation
} from "./function";

$(document).ready(function() {
  const baseUrl = "http://localhost/mini-chat/"; //* url de base pour effectuer une action
  const inputPassword = $("#signin-password");
  let connectedUserId = 0;

  /**
   * création de compte utilisateur
   */
  $("#form-inscription").submit(e => {
    e.preventDefault();

    const dataForm = {
      pseudo: $("#pseudo").val(),
      email: $("#signup-email").val(),
      password: $("#signup-password").val(),
      repassword: $("#repassword").val(),
      inscription: $("#inscription").val()
    };

    let isValidate = 1;

    if (!dataForm.pseudo) {
      $("#error-pseudo").text(" * Le champs est obligatoire.");
      isValidate = 0;
    } else {
      $("#error-pseudo").html("");
    }

    if (!dataForm.email) {
      $("#error-email").text(" * Le champs est obligatoire.");
      isValidate = 0;
    } else {
      $.ajax({
        type: "GET",
        url: `${baseUrl}users`,
        success: function(response) {
          const usersEmail = response.map(user => user.email);

          if (usersEmail.includes(dataForm.email)) {
            $("#error-email").text(
              " * L'e-mail que vous avez entré est déjà pris."
            );
            isValidate = 0;
          } else {
            $("#error-email").html("");
            isValidate = 1;
          }
        },
        error: function(response) {
          console.error(response.responseText);
        }
      });
    }

    if (!dataForm.password) {
      $("#error-password").text(" * Le champs est obligatoire.");
      isValidate = 0;
    } else {
      if (dataForm.password.length < 6) {
        $("#error-password").text(
          " * Le mot de passe doit avoir au moins 6 caractères."
        );
        isValidate = 0;
      } else {
        $("#error-password").html("");
      }
    }

    if (!dataForm.repassword) {
      $("#error-repassword").text(" * Le champs est obligatoire.");
      isValidate = 0;
    } else {
      if (dataForm.password != dataForm.repassword) {
        $("#error-repassword").text(
          " * Les deux mot de passe ne sont pas identiques."
        );
        isValidate = 0;
      } else {
        $("#error-repassword").html("");
      }
    }

    if (isValidate) {
      $.ajax({
        type: "POST",
        url: `${baseUrl}inscription`,
        data: dataForm,
        success: function(response) {
          $("#signin-email").val(dataForm.email);
          $("#signup-modal").modal("hide");
          $("#alert-user-added")
            .html(response.message)
            .fadeIn("slow")
            .fadeOut(5500);
        },
        error: function(response) {
          console.error(response.responseText);
        }
      });
    }
  });

  /**
   * connexion à une compte utilisateur
   */
  $("#form-connexion").submit(e => {
    e.preventDefault();

    $("#error_mail").html("");
    $("#error_pass").html("");

    const dataForm = {
      email: $("#signin-email").val(),
      password: inputPassword.val(),
      connexion: $("#connexion").val()
    };

    if (dataForm.email && dataForm.password) {
      $.ajax({
        type: "POST",
        url: `${baseUrl}connexion`,
        data: dataForm,
        success: function(response) {
          const { message, errorMail, errorPass, online, userId } = response;

          if (message)
            $("#alert-user-logged")
              .html(message)
              .fadeIn("slow")
              .fadeOut(5500);
          if (errorMail) {
            $("#error_mail").html(errorMail);
            inputPassword.val("");
          }

          if (errorPass) $("#error_pass").html(errorPass);

          if (online) {
            connectedUserId = userId;
            displayMainApp();
            loadConversation(connectedUserId, baseUrl);
            createConversation(connectedUserId, baseUrl);
          }
        },
        error: function(response) {
          console.error(response.responseText);
        }
      });
    }
  });

  /**
   * rechercher une conversation
   */
  $("#search").keyup(e =>
    findConversation(connectedUserId, e.target.value, baseUrl)
  );

  /**
   * déconnexion de l'utilisateur
   */
  $("#btn-deconnexion").click(() => {
    $("#deconnexion-modal").modal("hide");

    $.ajax({
      type: "GET",
      url: `${baseUrl}deconnexion`,
      success: function(response) {
        const { message } = response;
        if (message)
          $("#alert-user-logged")
            .html(message)
            .fadeIn("slow")
            .fadeOut(5500);
        displayLoginApp();
        $("#sidebar-content").html("");
        $("#main-message").html("");
        inputPassword.val("");
        inputPassword.attr("type", "password");
      },
      error: function(response) {
        console.error(response.responseText);
      }
    });
  });

  /**
   * Afficher ou cacher mot de passe
   */
  $("#show-password").click(e =>
    !e.target.checked
      ? inputPassword.attr("type", "password")
      : inputPassword.attr("type", "text")
  );

  testConnexion(connectedUserId, baseUrl); //! teste de la connexion
});
