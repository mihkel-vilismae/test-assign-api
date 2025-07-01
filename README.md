**NOTE:  ISSUES ACTIVELY BEING SOLVED.**
Also: project is not Laravel as I mistakenly thought.

- How to set up and run the project.
  - add --passwords_etc-- to .env
  - run migration command
  - run seed command

- sudo docker compose up

- API endpoints and their usage.
  - /api/filters/delete/{id}
  - /api/filters/update/{id}
  - /api/filters/create
  - /api/filters/get

--
sudo docker compose down

sudo  docker stop $(docker ps -a -q)
sudo docker rm $(docker ps -a -q)
sudo docker system prune --all --volumes --force

php bin/console cache:clear
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

sudo docker compose build --no-cache
sudo docker compose up -d
sudo docker exec -it ee123aebf60a  /bin/sh


--


