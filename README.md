## XM PHP Exercise v21.0.5

### Steps to install & run
Clone repo and from root directory build docker images `docker-compose up -d`

### Notes
- The reason for not having Company title but a symbol in the Email message is that Symfony validation was used and I had two choices that I did not have time to accomplish in two working days; completely refactor validation. Or do two consecutive API calls to Companies data, that would be overhead.
- Wished I had more time to do better tests, my strategy was "rushing" to get features done, quite opposite of TDD.
- Docker setup used; https://github.com/tzoro/docker-php8-debian10-mysql