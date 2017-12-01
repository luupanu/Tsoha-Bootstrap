// create links for tags & projects when the document loads
$(document).ready(function(){
  var tags = document.getElementsByClassName('tag');
  var projects = document.getElementsByClassName('project');

  var createLinksForAllTableRows = function(array){
    for (var i = 0; i < array.length; i++){
      createLinks(array[i]);
    }
  };

  createLinksForAllTableRows(tags);
  createLinksForAllTableRows(projects);
});

// disable paste on editables
$('.editable').on('paste', function(e){
  var paste = e.originalEvent.clipboardData.getData('text');
  var sel = window.getSelection().toString();
  var limit = 140;
  if (this.classList.contains('name')) limit = 50;
  if (paste.length + $(this).text().length - sel.length > limit) {
    e.preventDefault();
  }
});

// disable drag&drop paste on editables
$('.editable').on('drop', function(e){
    e.preventDefault();
});

// don't allow more than 50 or 140 chars on editables
$('.editable').on('keydown', function(e){
  var limit = 140;
  if (this.classList.contains('name')) limit = 50;
  var sel = window.getSelection().toString();

  if($(this).text().length - sel.length >= limit
      // unless it's backspace, tab, enter, escape, del
      && ($.inArray(e.keyCode, [8, 9, 13, 27, 46]) == -1)
      // or function keys
      && (e.keyCode < 112 || e.keyCode > 123)
      // or alt, ctrl & cmd
      && !e.altKey && !e.ctrlKey && !e.metaKey)
      // prevent rest
  {
    e.preventDefault(); 
  }
});

// leave focus when user presses enter
$('.editable').keypress(function(e){
  if (e.which === 13){
    e.preventDefault();
    this.blur();
  }
});

// wip JSON
$('input[id=jsonUpload]').click(function(e){
  myFile = e.target.files[0];
  console.log(myFile);
});

// remove hashtags when focused
$('.tag').focus(function (){
  this.innerText = this.innerText.replace(/#+/g, '');
});
// removes links when focused
$('.project').focus(function (){
  this.innerText = this.innerText;
});

// handle strings in editables
$('.editable')
  .focus(function(){
    $(this).data('original', $(this).text());
  })

  .blur(function(){
    var string = this.innerText = this.innerText.trim();
    var original = $(this).data('original');

    // replace multiple spaces with just one and split
    var array = string.replace(/\s+/g, ' ').split(' ');
    // don't allow more characters than limit
    var limit = 50;
    if (this.classList.contains('project')) limit = 140;
    array = array.filter(s => s.length <= limit);

    // return if nothing changed
    if (original === array.join(' ')) {
      createLinks(this);
      return;
    }

    if (this.classList.contains('tag') || this.classList.contains('project')){
      // delete duplicates
      array = [...new Set(array)];
    }

    this.innerText = array.join(' ');

    var form = document.getElementById('sample-edit');
    form.action = form.action.replace('id', this.parentElement.id);
    var param = document.getElementById('parameter');
    param.name = this.headers;
    param.value = this.innerText;
    form.submit();
});

function createLinks(parent){
  parent.innerText = parent.innerText.trim();
  if (!parent.classList.contains('tag') && !parent.classList.contains('project')) return;
  if (!parent.innerText) return;
  console.log(parent + ' \'' + parent.innerText + '\' ' + parent.innerText.length);

  array = parent.innerText.split(' ');
  parent.innerText = '';
  var prefix = '';
  if (parent.classList.contains('tag')) {
    prefix = '#';
  }

  for (var i = 0; i < array.length; i++) {
    var link = document.createElement('a');
    link.setAttribute('href', '#' + array[i]);
    link.setAttribute('contenteditable', false);
    link.setAttribute('onclick', 'event.preventDefault();');
    if (i+1 < array.length){
      link.innerText = prefix + array[i] + ' ';
    } else {
      link.innerText = prefix + array[i];
    }
    parent.appendChild(link);
  }
}