function f_setfocus()
{
var bFound = false;

  // for each form
  for (f=0; f < document.forms.length; f++)
  {
    // for each element in each form
    for(i=0; i < document.forms[f].length; i++)
    {
      // if it's not a hidden element
      if (document.forms[f][i].type != "hidden")
      {
        // and it's not disabled
        if (document.forms[f][i].disabled != true)
        {
            // set the focus to it
            document.forms[f][i].focus();
            var bFound = true;
        }
      }
      // if found in this element, stop looking
      if (bFound == true)
        break;
    }
    // if found in this form, stop looking
    if (bFound == true)
      break;
  }
 }