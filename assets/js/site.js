// disable paste on editables
$('.editable').on('paste', function(e){
    e.preventDefault();
  });
// don't allow more than 50 chars on editables
$('.editable').on('keydown', function(e){
  if($(this).text().length >= 50 && e.keyCode != 8) {
    e.preventDefault();
  }
})
// leave focus when user presses enter
$('.editable').keypress(function(e){
  if (e.which === 13){
    e.preventDefault();
    this.blur();
  }
});
// wip
$('input[id=jsonUpload]').click(function(e){
  myFile = e.target.files[0];
  console.log(myFile);
});
// remove hashtags when clicked
$('.editable').click(function (){
  this.innerText = this.innerText.replace(/#+/g, '');
});
// handle strings in editables
$('.editable').blur(function (){
  var string = this.innerText;

  // return if nothing here
  if (string.trim() === '') return;

  // replace multiple spaces with just one and split
  string = string.replace(/\s+/g, ' ').split(' ');
  var array = [];
  for (var i=0; i < string.length; i++){
    if (string[i].trim() === '' || string[i] == null) {
      continue;
    } else {
      array.push(string[i].trim());
    }
  }
  array.filter(a => a !== ' ');

  if (this.id === 'tag'){
    this.innerHTML = surroundWithTag('a', 'href', '#', array, '#');
  } else if (this.id === 'project'){
    this.innerHTML = surroundWithTag('a', 'href', '#', array, '');
  } else{
    this.innerText = array.join(' ').replace(/#+/g, '');
  }
});

function surroundWithTag(tag, attribute, value, array, char){
  console.log(array);
  var startBracket = '<' + tag + ' ' + attribute + '="' + value + '"' + '>';
  var endBracket = '</' + tag + '> ';
  var result = array.join(endBracket + startBracket + char);
  result = startBracket + result + endBracket;
  return startBracket + char + result + endBracket;
}