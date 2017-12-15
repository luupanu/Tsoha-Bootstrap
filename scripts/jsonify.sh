#!/bin/bash

# jsonifys an xml file with two different data sets

INPUT='samples.xml' 		# input xml file
OUTPUT='samples.json'		# output json file

tmp1='tmp00000001'			# temporary file names
tmp2='tmp00000002'			# temporary file names

xml_tag1='<file_name>'	# XML data set #1
xml_tag2='<duration '		# XML data set #2

# colours
if [ -t 1 ]; then
  random=$(((RANDOM%6)+1))
  colour=$(tput setaf $random)
  reset=$(tput sgr0)
fi

# parse the xml files and pipe output to temp files
grep -i $xml_tag1 $INPUT | cut -d\> -f2 | cut -d\< -f1 > $tmp1
grep -i $xml_tag2 $INPUT | cut -d\> -f2 | cut -d\< -f1 > $tmp2

last="$(cat $tmp1 | wc -l)"	# get the last line number
inc=1						# increment this variable each loop

# the main script. jsonify the two data sets
jsonify_me(){
echo "["
while read -r line; do
  if ! read -u 3 line2
  	then
  	  break
  fi
  echo "  {"
  echo -n "    \"filename\": "
  echo "\"$line\","
  echo -n "    \"duration\": "
  echo "\"$line2\""
  if [ "$inc" -eq "$last" ]
    then
  	  echo "  }"
  	else 
  	  echo "  },"
  fi
  inc=$((inc+1))
  done < $tmp1 3< $tmp2
echo "]"
}

jsonify_me > $OUTPUT

# delete temp files
rm $tmp1
rm $tmp2

# verbose mode: -v
while getopts ":v" opt; do
  case $opt in
    v)
      echo "${colour}jsonify.sh: ${reset}new file $OUTPUT from $INPUT" >&2
      ;;
    \?)
      echo "Invalid option: $OPTARG" >&2
      ;;
  esac
done