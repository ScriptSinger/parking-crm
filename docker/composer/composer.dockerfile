FROM composer:2

ARG UID=1000
ARG GID=1000

RUN addgroup -g ${GID} composergroup && \
    adduser -u ${UID} -G composergroup -s /bin/sh -D composeruser


USER composeruser