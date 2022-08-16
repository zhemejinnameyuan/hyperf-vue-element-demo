#!/bin/bash
php_command="/usr/local/opt/php@7.4/bin/php"
work_path=$(cd `dirname $0`; pwd)

function stopHyperf() {
    pids=`ps -ef | grep -i $work_path"/hyperf.php start" | grep -v "grep" | awk '{print $2}'`
    echo "停止服务"
    for id in ${pids[*]}
    do
    echo "kill--"${id}
    kill -9 ${id}
    done
}

function runHyperf() {
    echo "切换目录:"${work_path}
    cd $work_path
    echo "删除runtime/container"
    rm -rf ../runtime/container/
    echo "启动服务"
    nohup $php_command $work_path"/hyperf.php" start &
}



# 帮助文档
help()
{
    cat <<- EOF
    Usage:
        help [options] [<command_name>]Options:
    Options:
        stop      Stop  server
        start     Start  server
        restart   Restart  server
        help      Help document
EOF
    return $!
}

case $1 in
  'stop')
    stopHyperf
  ;;
  'start')
    runHyperf
  ;;
  'restart')
    stopHyperf
    runHyperf
  ;;
  *)
    help
  ;;
esac

exit 0

