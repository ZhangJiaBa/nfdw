#!/bin/bash
#@author Chad
#@createdAt 2017-05-20 15:52
#@description This script is for CI build of changqi-api.

DOCKER_DIR='/var/jenkins_home/workspace/shengxiang-api'
DB_NAME='shengxiang_admin'
TEST_DB_NAME='shengxiang_admin'
CONTAINER_PREFIX='shengxiang'

# ensure services are started
tryStartServices() {
  cd $DOCKER_DIR
  docker-compose up -d
  return $?
}

waitUntilMysqlIsReady() {
  local MYSQL_IS_OK=1
  while [ $MYSQL_IS_OK -ne 0 ];
  do
    docker exec -i shengxiang_db_1 mysql -e "show databases" > /dev/null 2>&1
    MYSQL_IS_OK=$?
    echo 'MySQL is not ready yet...'
    sleep 1
  done

  echo 'Mysql is ready.'
  return 0
}

# updateSrcCodeDependencies
updateSrcCodeDependencies() {
  # TODO remove this
  # this is just for fixing composer update post cmd "'cache:clear --no-warmup'",
  # which prompting "An exception occured in driver: SQLSTATE[HY000] [1049] Unknown database 'xxxx'"
  docker exec shengxiang_db_1 mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';"

  docker exec -i shengxiang_api composer config -g repo.packagist composer https://packagist.phpcomposer.com
  docker exec -i shengxiang_api composer update --prefer-dist
  return $?
}

ensureNewDatabaseForCi() {
  docker exec shengxiang_db_1 mysql -e "DROP DATABASE IF EXISTS \`${TEST_DB_NAME}\`";
  return 0
}

# upgrade database
upgradeDatabase() {
  docker exec shengxiang_db_1 mysql -e "CREATE DATABASE IF NOT EXISTS \`${TEST_DB_NAME}\` CHARACTER SET \"utf8mb4\" COLLATE \"utf8mb4_general_ci\";"
  if [ $? -ne 0 ] ; then
    return $?
  fi

  docker exec -i shengxiang_api php artisan migrate
  return 0
}

# ensure directoy access permission
ensureDirPerm(){

  docker exec -i shengxiang_api chmod -R 777 bootstrap storage

  return $?
}

# clear cache
clearCache(){
  docker exec -i shengxiang_api php artisan cache:clear
  return 0
}

fullBuild() {

  docker exec -i shengxiang_api ./vendor/bin/phing

  # replace '/var/www/html/' with '' in xml log files
  # so that Jenkins plugins can read them properly
  docker exec -i shengxiang_api sh -c 'for f in `ls build/logs`; do sed -i "s/\/var\/www\/html\///" "build/logs/$f"; done'

  return $?
}

stopServices() {
  cd $DOCKER_DIR
  docker-compose stop
  return $?
}

tryStartServices \
&& updateSrcCodeDependencies \
&& ensureNewDatabaseForCi \
&& upgradeDatabase \
&& ensureDirPerm \
&& clearCache \
&& ensureDirPerm \
&& fullBuild \
&& stopServices

exit $?