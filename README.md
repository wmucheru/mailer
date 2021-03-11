# Mailer
Mailer API powerd by Codeigniter

# Endpoint

BaseURL is set in 

`api/v1/mailer [POST]`: Send mail; see params below

### Recipient
- `email`: Recipient email
- `name`: Recipient name
- `subject`: Email subject
- `body`: Email body

### Sender 
- `from_email`: From email
- `from_name`: From name

### Reply-to
- `reply_email`: Reply-to email
- `reply_name`: Reply-to name

### Meta
- `protocol`: Protocol of email (mail, smtp etc)

### SMTP (applies if protocol is `smtp`)
- `smtp_host`: Host
- `smtp_user`: User
- `smtp_pass`: Password
- `smtp_port`: Port
- `smtp_crypto`: Crypto