#View the project's README.md for invocation/maintenance rules.

AC_PREREQ([2.69])
AC_INIT([web-api-base-sdk-php], [1.0.0], [jhollenbeck@nabancard.com])

#make sure our source is there
AC_CONFIG_SRCDIR([lib])

# Check for custom binaries that we require for the make commands
AC_CHECK_PROG(DOCKER_BIN, docker, yes)
if test x"$DOCKER_BIN" != x"yes" ; then
    AC_MSG_ERROR([Docker was not found in your PATH. Please install.])
fi

AC_CONFIG_FILES([Makefile])
AC_OUTPUT
