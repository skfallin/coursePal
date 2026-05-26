(function () {
  function notice(form, message) {
    var existing = form.parentElement.querySelector(".notice, .error");
    if (!existing) {
      existing = document.createElement("p");
      existing.className = "notice";
      form.parentElement.insertBefore(existing, form);
    }
    existing.textContent = message;
    existing.className = "notice";
  }

  function value(form, name) {
    var field = form.querySelector('[name="' + name + '"]');
    return field ? field.value.trim() : "";
  }

  function checkedValues(form, name) {
    return Array.from(form.querySelectorAll('[name="' + name + '"]:checked')).map(function (field) {
      return field.value;
    });
  }

  document.addEventListener("submit", function (event) {
    var form = event.target;
    var action = form.getAttribute("action") || "";
    var demoAction = form.getAttribute("data-demo-action") || "";

    if (!demoAction && form.method.toLowerCase() !== "post") {
      return;
    }

    event.preventDefault();

    if (demoAction === "register" || action.indexOf("register") !== -1) {
      var password = value(form, "password");
      var passwordVerify = value(form, "password_verify");
      if (!value(form, "first_name") || !value(form, "last_name") || !value(form, "username") || !value(form, "email")) {
        notice(form, "Complete all registration fields.");
        return;
      }
      if (password.length < 8) {
        notice(form, "Password must be at least 8 characters.");
        return;
      }
      if (password !== passwordVerify) {
        notice(form, "Password and password verification must match.");
        return;
      }
      if (checkedValues(form, "categories[]").length === 0) {
        notice(form, "Choose at least one course category.");
        return;
      }
      localStorage.setItem("coursepal_demo_user", value(form, "username"));
      notice(form, "Demo account created. You can now use the login form.");
      return;
    }

    if (demoAction === "login" || action.indexOf("index") !== -1) {
      var username = value(form, "username");
      if (!username || !value(form, "password")) {
        notice(form, "Enter your username and password.");
        return;
      }
      localStorage.setItem("coursepal_demo_user", username);
      window.location.href = "account.html";
      return;
    }

    if (demoAction === "update-account" || action.indexOf("update-account") !== -1 || action.indexOf("update_account") !== -1) {
      notice(form, "Demo account details saved in the browser preview.");
      return;
    }

    if (demoAction === "edit-course" || action.indexOf("edit-course") !== -1 || action.indexOf("edit_course") !== -1) {
      notice(form, "Demo course saved in the browser preview.");
      return;
    }

    notice(form, "This static Vercel demo cannot save to MySQL. Use the local PHP/MySQL version for real database changes.");
  });
})();
