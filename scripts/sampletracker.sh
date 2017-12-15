#!/bin/bash

#	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 	
#	* sampletracker.sh											                         		*
#	*																                                   	*
# * 	a script to track your sample library - uses afinfo and outputs	*
#	*	  to an xml file 				              		                        *
#	*															                                  		*
# * 	variables: 	TEMPFILE	- a temporary file the script uses	     	*
#	*				        OUTPUT 		- the .xml output file 				           	*
#	* 	add more file types than below if necessary					           	*
#	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

# variables
DIRECTORY="."	# default = current directory
TEMPFILE=.tmp000001
OUTPUT=samples.xml

# colours
if [ -t 1 ]; then
  random=$(((RANDOM%6)+1))
  purple=$(tput setaf $random)
  reset=$(tput sgr0)
fi

# the script
find -E $DIRECTORY -iregex '.*(wav|aif|mp3|m4a|3ga|amr)' > $TEMPFILE
IFS=$'\n' read -d '' -r -a lines < $TEMPFILE
afinfo -b -x "${lines[@]}" > $OUTPUT

# verbose mode: -v
while getopts ":v" opt; do
  case $opt in
    v)
      echo -e "${purple}sampletracker.sh: ${reset}$(wc -l $TEMPFILE | awk '{print $1}') sample(s) found. Output file: $OUTPUT." >&2
      ;;
    \?)
      echo "Invalid option: $OPTARG" >&2
      ;;
  esac
done
rm $TEMPFILE