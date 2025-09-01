# Makefile for WordPress theme ppfeufer

# Default goal and help message for the Makefile
.DEFAULT_GOAL := help

# Theme information
theme_name = Theme Name
theme_slug = theme-name

# Git repository URLs
theme_repo_url = https://github.com/ppfeufer/$(theme_slug)-wordpress-theme
theme_issues_url = $(theme_repo_url)/issues

# Help message for the Makefile
.PHONY: help
help::
	@echo "$(TEXT_BOLD)$(theme_name)$(TEXT_BOLD_END) Makefile"
	@echo ""
	@echo "$(TEXT_BOLD)Usage:$(TEXT_BOLD_END)"
	@echo "  make [command]"
	@echo ""
	@echo "$(TEXT_BOLD)Commands:$(TEXT_BOLD_END)"

# Include the configurations
include .make/conf.d/*.mk
