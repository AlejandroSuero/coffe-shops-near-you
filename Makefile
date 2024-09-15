ifeq ($(OS),Windows_NT)
	GREEN= [00;32m
	RESTORE= [0m
	GO_FILES=$(shell dir /b /s *.go | findstr /v /i /e ".git")
else
	GREEN="\033[00;32m"
	RESTORE="\033[0m"
	GO_FILES=$(shell find . -name "*.go" -and  -not -name ".git")
endif

# make the output of the message appear green
define style_calls
	$(eval $@_msg = $(1))
	echo ${GREEN}${$@_msg}${RESTORE}
endef

default_target: help

build:
	@$(call style_calls,"Building docker image")
	@docker compose build
	@$(call style_calls,"Done")
.PHONY: docker-build

run: build
	@$(call style_calls,"Running docker image")
	@docker compose up -d
	@$(call style_calls,"Done ✅")
	@echo ""
	@$(call style_calls,"App is running at http://localhost:8080")
.PHONY: run

stop:
	@$(call style_calls,"Stopping docker image")
	@docker compose down
	@$(call style_calls,"Done ✅")
.PHONY: stop

spell:
	@$(call style_calls,"Running codespell check")
	@codespell --quiet-level=2 --check-hidden --skip=./.git --skip=./node_modules .
	@$(call style_calls,"Done ✅")
	@echo ""
.PHONY: spell

spell-write:
	@$(call style_calls,"Running codespell write")
	@codespell --quiet-level=2 --check-hidden --skip=./.git --skip=./node_modules --write-changes .
	@$(call style_calls,"Done ✅")
	@echo ""
.PHONY: spell-write

lint: spell
	@$(call style_calls,"Running editorconfig check")
	@editorconfig-checker src/ Makefile README.md
	@$(call style_calls,"Done ✅")
.PHONY: lint

help:
	@echo "Usage:"
	@echo "  make build         - build docker image"
	@echo "  make run           - run docker image"
	@echo "  make stop          - stop docker image"
	@echo "  make spell         - run codespell check"
	@echo "  make spell-write   - run codespell write"
	@echo "  make lint          - run editorconfig check"
.PHONY: help
