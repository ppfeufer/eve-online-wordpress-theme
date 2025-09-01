# Run pre-commit checks
.PHONY: pre-commit-checks
pre-commit-checks: pre-commit-install
	@echo "Running pre-commit checks …"
	@pre-commit run --all-files

# Update pre-commit configuration
.PHONY: pre-commit-update
pre-commit-update:
	@echo "Updating pre-commit configuration …"
	@pre-commit autoupdate --freeze

# Install pre-commit hook
.PHONY: pre-commit-install
pre-commit-install:
	@echo "Installing pre-commit hook …"
	@pre-commit install

# Uninstall pre-commit hook
.PHONY: pre-commit-uninstall
pre-commit-uninstall:
	@echo "Uninstalling pre-commit hook …"
	@pre-commit uninstall

# Help message for the Pre-Commit commands
.PHONY: help
help::
	@echo "  $(TEXT_UNDERLINE)pre-commit:$(TEXT_UNDERLINE_END)"
	@echo "    pre-commit-checks         Run pre-commit checks"
	@echo "    pre-commit-install        Install pre-commit hook"
	@echo "    pre-commit-uninstall      Uninstall pre-commit hook"
	@echo "    pre-commit-update         Update pre-commit configuration"
	@echo ""
