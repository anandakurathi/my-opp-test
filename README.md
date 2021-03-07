## Media upload 

Upload Ad media to application


## Instaltion 
```
git clone https://github.com/anandakurathi/my-opp-test.git
cd my-opp-test
cp .env.example .env

docker-compose build app
docker-compose up -d

docker-compose exec app compose install
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan optimize:clear
```
## Access your app at 
After all above steps successfully run. You will able to access the application http://localhost:8080

### endpoints are 

GET http://localhost:8080/api/providers
GET http://localhost:8080/api/ads-list

POST http://localhost:8080/api/upload
Params are
name,
provider,
image_file (file type) /
video_file (file type)

Note: Preview of vido is not covered.

if any question / assistane needed mail me anand.akurathi@gmail.com
