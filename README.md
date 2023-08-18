# Appointment REST-API

All routes (expect auth) require auth. Here list of routes:
* Appointments:
  + GET|HEAD api/v1/appointments - show all appointments (only for admin);
  + POST api/v1/appointments - store appointment (onlu client and admin):
    - client_id (required);
    - specialist_id (required);
  + GET|HEAD api/v1/appointments/{appointment} - show appointment;
  + PUT|PATCH api/v1/appointments/{appointment} - update appointment (only for admins, now disabled):
    - client_id;
    - specialist_id;
  + DELETE api/v1/appointments/{appointment} - delete appointment (only for client and admin);
* Auth:
  + GET|HEAD api/v1/auth - get current user data (if user already auth);
  + POST api/v1/auth/login - login by username_email and password fields:
    - 'username_email' or 'email_username' or 'username' or 'email';
    - password;
  + POST api/v1/auth/register/client - register as client:
    - username (required); 
    - email (required);
    - first_name (required);
    - last_name (required);
    - patronymic;
    - phone (required);
    - password (required);
    - password_confimation (required);
  + POST api/v1/auth/register/specialist - register as specialist:
    - username (required);
    - email (required);
    - first_name (required);
    - last_name (required);
    - patronymic;
    - schedule (required);
    - description (required);
    - password (required);
    - password_confimation (required);
* Clients:
  + GET|HEAD api/v1/clients - get add clients list (only for admin);
  + POST api/v1/clients - store client (only for admin):
    - phone (required);
  + GET|HEAD api/v1/clients/{client} - show client information;
  + PUT|PATCH api/v1/clients/{client} - update client information (only for admin or same client):
    - phone;
  + DELETE api/v1/clients/{client} - delete client (only for admin);
  + GET|HEAD api/v1/clients/{client}/appointments - show clients appointments;
  + POST api/v1/clients/{client}/appointments - store appointment as client:
    - specialist_id (required);
  + GET|HEAD api/v1/clients/{client}/appointments/{appointment} - show client appointment;
  + DELETE api/v1/clients/{client}/appointments/{appointment} - delete client appointment;
* Specialists:
  + GET|HEAD api/v1/specialists - show all specialists;
  + POST api/v1/specialists - store specialist (only for admin):
    - schedule (required);
    - description (required);
  + GET|HEAD api/v1/specialists/{specialist} - show specialist;
  + PUT|PATCH api/v1/specialists/{specialist} - update specialist information (only for admin or current specialist):
    - schedule;
    - description;
  + DELETE api/v1/specialists/{specialist} - delete specialist (only for admin or current specialist);
  + GET|HEAD api/v1/specialists/{specialist}/appointments - show all appointments of current specialist;
  + GET|HEAD api/v1/specialists/{specialist}/appointments/{appointment} - get current specialist appointment;
  + DELETE api/v1/specialists/{specialist}/appointments/{appointment} - delete appointment on current specialist.
