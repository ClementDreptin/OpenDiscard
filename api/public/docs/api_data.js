define({ "api": [
  {
    "type": "delete",
    "url": "/messages/:id/",
    "title": "Delete",
    "group": "Messages",
    "description": "<p>Deletes a Message.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"message\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"content\": \"My Super Cool Message Updated\",\n    \"created_at\": \"2020-05-28 17:05:47\",\n    \"updated_at\": \"2020-05-28 17:10:34\",\n    \"user_id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"channel_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "MessageNotFound",
            "description": "<p>The UUID of the Message was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotMessageAuthor",
            "description": "<p>A Non-Author tries to delete the Message.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "MessageNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Message with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotMessageAuthor-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Message Author can delete this Message.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/MessageController.php",
    "groupTitle": "Messages",
    "name": "DeleteMessagesId"
  },
  {
    "type": "patch",
    "url": "/messages/:id/",
    "title": "Update",
    "group": "Messages",
    "description": "<p>Updates a Message.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>The new Message's content.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"content\": \"My Super Cool Message Updated\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n  \"type\": \"resource\",\n  \"message\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"content\": \"My Super Cool Message Updated\",\n    \"created_at\": \"2020-05-28 17:05:47\",\n    \"updated_at\": \"2020-05-28 17:10:34\",\n    \"user_id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"channel_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "MessageNotFound",
            "description": "<p>The UUID of the Message was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotMessageAuthor",
            "description": "<p>A Non-Author tries to update the Message.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "MessageNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Message with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotMessageAuthor-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Message Author can modify this Message.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/MessageController.php",
    "groupTitle": "Messages",
    "name": "PatchMessagesId"
  },
  {
    "type": "post",
    "url": "/channels/:id/messages/",
    "title": "Create",
    "group": "Messages",
    "description": "<p>Creates a Message.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "content",
            "description": "<p>The Message's content.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"content\": \"My Super Cool Message\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n  \"type\": \"resource\",\n  \"message\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"content\": \"My Super Cool Message\",\n    \"created_at\": \"2020-05-28 17:05:47\",\n    \"updated_at\": \"2020-05-28 17:05:47\",\n    \"user_id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"channel_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "TextChannelNotFound",
            "description": "<p>The UUID of the Text Channel was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotServerMember",
            "description": "<p>A Non-Member tries to create a Message.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ServerNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Text Channel with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotServerMember-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only Members can create Messages.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/MessageController.php",
    "groupTitle": "Messages",
    "name": "PostChannelsIdMessages"
  },
  {
    "type": "delete",
    "url": "/servers/:id/",
    "title": "Delete",
    "group": "Servers",
    "description": "<p>Deletes a Server.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"server\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Server\",\n    \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "ServerNotFound",
            "description": "<p>The UUID of the Server was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotServerOwner",
            "description": "<p>A Member tries to delete the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ServerNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Server with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotServerOwner-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Server's Owner can modify the Server.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/ServerController.php",
    "groupTitle": "Servers",
    "name": "DeleteServersId"
  },
  {
    "type": "delete",
    "url": "/servers/:server_id/users/:user_id/",
    "title": "Remove User",
    "group": "Servers",
    "description": "<p>Removes a User from a Server.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"server\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Server\",\n    \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  },\n  \"user\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"username\": \"AlbertEinsteinUpdated\",\n    \"email\": \"albert.einstein@physics.com\",\n    \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\"\n  }\n}",
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
            "field": "ServerNotFound",
            "description": "<p>The UUID of the Server was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNotFound",
            "description": "<p>The UUID of the User was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNotInServer",
            "description": "<p>The User is not a Member of the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotMemberOfServer",
            "description": "<p>The token Owner is not a Member of the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "ServerOwnerLeaves",
            "description": "<p>The Server Owner tries to leave the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "MemberKicksMember",
            "description": "<p>A Member tries to kick another Member from the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ServerNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Server with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "UserNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"User with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "UserNotInServer-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"The User with ID db0916fa-934b-4981-9980-d53bed190db3 is not a Member of the Server with ID db0916fa-934b-4981-9980-d53bed190db3.\"\n}",
          "type": "json"
        },
        {
          "title": "NotMemberOfServer-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"You are not allowed to kick Members from a Server you are not a Member of.\"\n}",
          "type": "json"
        },
        {
          "title": "ServerOwnerLeaves-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Server Owners can't leave their own servers.\"\n}",
          "type": "json"
        },
        {
          "title": "MemberKicksMember-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Server's Owner can kick Members.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/ServerController.php",
    "groupTitle": "Servers",
    "name": "DeleteServersServer_idUsersUser_id"
  },
  {
    "type": "get",
    "url": "/servers/",
    "title": "Get",
    "group": "Servers",
    "description": "<p>Gets all the Servers the token Owner is a Member of.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resources\",\n  \"servers\": [\n    {\n      \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n      \"name\": \"My Super Cool Server\",\n      \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n      \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n    },\n    {\n      \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n      \"name\": \"My Other Super Cool Server\",\n      \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n      \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n    }\n  ]\n}",
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
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/ServerController.php",
    "groupTitle": "Servers",
    "name": "GetServers"
  },
  {
    "type": "patch",
    "url": "/servers/:id/",
    "title": "Update",
    "group": "Servers",
    "description": "<p>Updates a Server's information.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>The new Server's name.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "image_url",
            "description": "<p>The new URL of the Server's image.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"name\": \"My Super Cool Server Updated\",\n  \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"server\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Server Updated\",\n    \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "ServerNotFound",
            "description": "<p>The UUID of the Server was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotServerOwner",
            "description": "<p>A Member tries to modify the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ServerNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Server with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotServerOwner-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Server's Owner can modify the Server.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/ServerController.php",
    "groupTitle": "Servers",
    "name": "PatchServersId"
  },
  {
    "type": "post",
    "url": "/servers/",
    "title": "Create",
    "group": "Servers",
    "description": "<p>Creates a Server.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The Server's name.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "image_url",
            "description": "<p>The URL of the Server's image.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"name\": \"My Super Cool Server\",\n  \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n  \"type\": \"resource\",\n  \"server\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Server\",\n    \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/ServerController.php",
    "groupTitle": "Servers",
    "name": "PostServers"
  },
  {
    "type": "put",
    "url": "/servers/:server_id/users/:user_id/",
    "title": "Add User",
    "group": "Servers",
    "description": "<p>Adds a User to a Server.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n  \"type\": \"resource\",\n  \"server\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Server\",\n    \"image_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"owner_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  },\n  \"user\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"username\": \"AlbertEinsteinUpdated\",\n    \"email\": \"albert.einstein@physics.com\",\n    \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\"\n  }\n}",
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
            "field": "ServerNotFound",
            "description": "<p>The UUID of the Server was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserNotFound",
            "description": "<p>The UUID of the User was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "UserAlreadyInServer",
            "description": "<p>The User is already in the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotMemberOfServer",
            "description": "<p>The token Owner is not a Member of the Server.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ServerNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Server with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "UserNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"User with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "UserAlreadyInServer-Response:",
          "content": "HTTP/1.1 422 UNPROCESSABLE ENTITY\n{\n  \"type\": \"error\",\n  \"error\": 422,\n  \"message\": \"The User with ID db0916fa-934b-4981-9980-d53bed190db3 is already a Member of the Server with ID db0916fa-934b-4981-9980-d53bed190db3.\"\n}",
          "type": "json"
        },
        {
          "title": "NotMemberOfServer-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"You are not allowed to add Users to a Server you are not a Member of.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/ServerController.php",
    "groupTitle": "Servers",
    "name": "PutServersServer_idUsersUser_id"
  },
  {
    "type": "delete",
    "url": "/channels/:id/",
    "title": "Delete",
    "group": "Text_Channels",
    "description": "<p>Deletes a Text Channel.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"text_channel\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Text Channel Updated\",\n    \"server_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "TextChannelNotFound",
            "description": "<p>The UUID of the Text Channel was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotServerOwner",
            "description": "<p>A Member tries to delete the Text Channel.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "TextChannelNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Text Channel with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotServerOwner-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Server's Owner can delete Text Channels.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/TextChannelController.php",
    "groupTitle": "Text_Channels",
    "name": "DeleteChannelsId"
  },
  {
    "type": "patch",
    "url": "/channels/:id/",
    "title": "Update",
    "group": "Text_Channels",
    "description": "<p>Updates a Text Channel's information.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "name",
            "description": "<p>The new Text Channel's name.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"name\": \"My Super Cool Text Channel Updated\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"text_channel\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Text Channel Updated\",\n    \"server_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "TextChannelNotFound",
            "description": "<p>The UUID of the Text Channel was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotServerOwner",
            "description": "<p>A Member tries to modify the Text Channel.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "TextChannelNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Text Channel with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotServerOwner-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Server's Owner can modify Text Channels.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/TextChannelController.php",
    "groupTitle": "Text_Channels",
    "name": "PatchChannelsId"
  },
  {
    "type": "post",
    "url": "/servers/:id/channels/",
    "title": "Create",
    "group": "Text_Channels",
    "description": "<p>Creates a Text Channel.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The Text Channel's name.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"name\": \"My Super Cool Text Channel\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 CREATED\n{\n  \"type\": \"resource\",\n  \"text_channel\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"name\": \"My Super Cool Text Channel\",\n    \"server_id\": \"db0916fa-934b-4981-9980-d53bed190db3\"\n  }\n}",
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
            "field": "ServerNotFound",
            "description": "<p>The UUID of the Server was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "NotServerOwner",
            "description": "<p>A Member tries to create a Text Channel.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "ServerNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"Server with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "NotServerOwner-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Only the Server's Owner can create Text Channels.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/TextChannelController.php",
    "groupTitle": "Text_Channels",
    "name": "PostServersIdChannels"
  },
  {
    "type": "patch",
    "url": "/users/:id/",
    "title": "Update",
    "group": "Users",
    "description": "<p>Updates a User's information.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "username",
            "description": "<p>The new User's username.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "avatar_url",
            "description": "<p>The new URL of the User's avatar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"username\": \"AlbertEinsteinUpdated\",\n  \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The User's token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Bearer Token:",
          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"user\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"username\": \"AlbertEinsteinUpdated\",\n    \"email\": \"albert.einstein@physics.com\",\n    \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n  }\n}",
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
            "field": "UserNotFound",
            "description": "<p>The UUID of the User was not found.</p>"
          },
          {
            "group": "Error 4xx",
            "optional": false,
            "field": "InvalidToken",
            "description": "<p>The token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "UserNotFound-Response:",
          "content": "HTTP/1.1 404 NOT FOUND\n{\n  \"type\": \"error\",\n  \"error\": 404,\n  \"message\": \"User with ID db0916fa-934b-4981-9980-d53bed190db3 doesn't exist.\"\n}",
          "type": "json"
        },
        {
          "title": "InvalidToken-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Token expired.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/UserController.php",
    "groupTitle": "Users",
    "name": "PatchUsersId"
  },
  {
    "type": "post",
    "url": "/users/signin/",
    "title": "Sign in",
    "group": "Users",
    "description": "<p>Signs a User in.</p>",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The e-mail address and password in the Basic Auth format.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Basic Auth:",
          "content": "{\n  \"Authorization\": \"Basic QWxhZGRpbjpPcGVuU2VzYW1l\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"type\": \"resource\",\n  \"user\": {\n    \"id\": \"db0916fa-934b-4981-9980-d53bed190db3\",\n    \"username\": \"AlbertEinstein\",\n    \"email\": \"albert.einstein@physics.com\",\n    \"avatar_url\": \"/images/c29eaa26-3fd1-4b66-aafe-60b571009d0d\",\n    \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJhcGlfcGxheWVyIiwic3ViIjoiZ2FtZSIsImF1ZCI6InBsYXllciIsImlhdCI6MTU4NDc0NTQ0NywiZXhwIjoxNTg0NzU2MjQ3fQ.vkaSPuOdb95IHWRFda9RGszEflYh8CGxhaKVHS3vredJSl2WyqqNTg_VUbfkx60A3cdClmcBqmyQdJnV3-l1xA\"\n  }\n}",
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
            "field": "InvalidCredentials",
            "description": "<p>The e-mail address or password are incorrect.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "InvalidCredentials-Response:",
          "content": "HTTP/1.1 401 UNAUTHORIZED\n{\n  \"type\": \"error\",\n  \"error\": 401,\n  \"message\": \"Email or password are incorrect.\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "src/control/UserController.php",
    "groupTitle": "Users",
    "name": "PostUsersSignin"
  },
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
