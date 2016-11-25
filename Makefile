NAME = $(notdir $(shell pwd))

default: clean build cp-artifacts test

build:
	docker build -t $(NAME) .

cp-artifacts:
	@docker create --name $(NAME) $(NAME)
	@docker cp $(NAME):/phapp/vendor ./
	@docker rm -fv $(NAME)

clean:
	-@docker rm -fv $(NAME)
	-@docker rmi -f $(NAME)
	-@rm -rf vendor

test:
	@docker run -d -p 8080:80 -v $(shell pwd):/phapp --name phapp-react $(NAME) ./tests/_data/StubReactiveProject/public/index.php
	-@docker run --rm -it -v $(shell pwd):/phapp --link phapp-react:react $(NAME) ./vendor/bin/codecept run
	@docker rm -fv phapp-react
