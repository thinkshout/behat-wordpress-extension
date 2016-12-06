git stash -q --keep-index
./bin/phpcs.sh
RESULT=$?
git stash pop -q

[ $RESULT -ne 0 ] && exit 1
exit 0
