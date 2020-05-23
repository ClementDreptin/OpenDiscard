define({ "api": [
  {
    "type": "post",
    "url": "/users/signup/",
    "title": "Create",
    "group": "Users",
    "description": "<p>Creates a User.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>The User's username.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>The User's e-mail address.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>The User's password.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "avatar_url",
            "description": "<p>The URL of the User's avatar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"username\": \"AlbertEinstein\",\n  \"email\": \"albert.einstein@physics.com\",\n  \"password\": \"physics\",\n  \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n  \"type\": \"resource\",\n  \"user\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"username\": \"AlbertEinstein\",\n    \"email\": \"albert.einstein@physics.com\",\n    \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "EmailAlreadyTaken",
            "description": "<p>The e-mail address is already taken.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "EmailAlreadyTaken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"This e-mail address is already taken.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/UserController.php",
    "groupTitle": "Users",
    "name": "PostUsersSignup"
  }
] });