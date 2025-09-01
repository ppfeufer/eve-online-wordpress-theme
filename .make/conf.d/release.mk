# Create a new release archive
.PHONY: release-archive
release-archive:
	@echo "Creating a new release archive …"
	@rm -f $(theme_slug).zip
	@rm -rf $(theme_slug)/
	@rsync \
		-ax \
		--exclude-from=.make/rsync-exclude.lst \
		. \
		$(theme_slug)/
	@zip \
		-r \
		$(theme_slug).zip \
		$(theme_slug)/
	@rm -rf $(theme_slug)/

# Help message for the Release commands
.PHONY: help
help::
	@echo "  $(TEXT_UNDERLINE)Release:$(TEXT_UNDERLINE_END)"
	@echo "    release-archive           Create a release archive."
	@echo "                              The release archive ($(theme_slug).zip) will be created in the root"
	@echo "                              directory of the theme."
	@echo ""
