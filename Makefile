.PHONY: default
default: install test;

######## DOCKER TESTING CONFIG ##########
PROJECT_NAME=apigateway_generic_php_library
AWS_ACCESS_KEY_ID?='fake'
AWS_SECRET_ACCESS_KEY?='fake'

DOCKER_CMD_BASE := docker run -u`id -u` \
	--rm \
	-v $(PWD):/var/www \
	-t nab/$(PROJECT_NAME)

DOCKER_CMD_ARGS := make
######## END DOCKER TESTING CONFIG ##########

#Installation
install: vendor
	cp phpunit.xml.dist phpunit.xml

vendor:
	composer install

#Test/PHPCS
test: phpcs
	./vendor/bin/phpunit -c .

phpcs: vendor
	./vendor/bin/phpcs --ignore=./vendor/,.idea/ --extensions=php --standard=PSR2 .

################################### DOCKCER ###############################################################
#maintain a list of docker file hashes that we can use to determine if we should rebuild the image (when
#the dockerfile hash changes, rebuild the image).
dockerfilehashes/$(shell sha256sum Dockerfile | cut -f1 -d' '):
	docker build --build-arg DOCKER_USER_ID=`id -u` -t nab/$(PROJECT_NAME) .
	mkdir -p dockerfilehashes
	touch $@

docker-build: dockerfilehashes/$(shell sha256sum Dockerfile | cut -f1 -d' ')

docker-test: docker-build
	$(DOCKER_CMD_BASE) $(DOCKER_CMD_ARGS) test
