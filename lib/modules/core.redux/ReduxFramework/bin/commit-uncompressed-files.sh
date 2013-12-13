if [[ "$TRAVIS_PULL_REQUEST" == "false" && "$TRAVIS_JOB_NUMBER" == *.1 ]]; then
	
  	echo -e "Checking to make sure files are properly compressed.\n"

	v1=`find ReduxCore -type f | sort -u | xargs cat | md5sum`
	echo "$v1"
	grunt compileCSS
	grunt compileJS
	v2=`find ReduxCore -type f | sort -u | xargs cat | md5sum`
	echo "$v2"
	if [ "$v1" == "$v2" ]; then
	    echo "All files are properly compressed."
	else
		echo "Files are not the same. Need to commit back to the repo."
		#git config --global user.email "travis@travis-ci.org"
		#git config --global user.name "Travis"
		#git remote set-url origin "https://$GH_TOKEN@github.com/ReduxFramework/ReduxFramework.git"
		#git status
		#git commit -a "Committing compressed files back to repo."
		#git push origin HEAD:$TRAVIS_BRANCH
	fi

fi
