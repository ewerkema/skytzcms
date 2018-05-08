class FontTool extends ContentTools.Tools.Bold

  # Register the tool with the toolshelf
  ContentTools.ToolShelf.stow(@, 'font')

  # The tooltip and icon modifier CSS class for the tool
  @label = 'Font'
  @icon = 'font'

  # The Bold provides a tagName attribute we can override to make inheriting
  # from the class cleaner.
  @tagName = 'span'
  @cssStyle = 'fontFamily'

  @isApplied: (element, selection) ->
    # Return true if the tool is currently applied to the current
    # element/selection.
    if element.content is undefined or not element.content.length()
      return false

    # Store the styles of the span tag (if any)
    span = @getFirstSpanTag(element, selection)
    if (span == undefined || span == false)
      return false

    styles = 'span { ' + span.attr('style') + ' }'
    rules = @rulesForCssText(styles)

    return rules.style[@cssStyle] != ""

  @apply: (element, selection, callback) ->
    # Apply the tool to the specified element and selection

    # Store the selection state of the element so we can restore it once done
    element.storeState()

    # Add a fake selection wrapper to the selected text so that it appears to be
    # selected when the focus is lost by the element.
    selectTag = new HTMLString.Tag('span', {'class': 'ct--puesdo-select'})
    [from, to] = selection.get()
    element.content = element.content.format(from, to, selectTag)
    element.updateInnerHTML()

    # Set-up the dialog
    app = ContentTools.EditorApp.get()

    # Add an invisible modal that we'll use to detect if the user clicks away
    # from the dialog to close it.
    modal = new ContentTools.ModalUI(transparent=true, allowScrolling=true)

    modal.addEventListener 'click', () ->
      # Close the dialog
      @unmount()
      dialog.hide()

      # Remove the fake selection from the element
      element.content = element.content.unformat(from, to, selectTag)
      element.updateInnerHTML()

      # Restore the real selection
      element.restoreState()

      # Trigger the callback
      callback(false)

    # Measure a rectangle of the content selected so we can position the
    # dialog centrally to it.
    domElement = element.domElement()
    measureSpan = domElement.getElementsByClassName('ct--puesdo-select')
    rect = measureSpan[0].getBoundingClientRect()

    # Create the dialog
    dialog = @getDialog(element, selection)
    dialog.position([
      rect.left + (rect.width / 2) + window.scrollX,
      rect.top + (rect.height / 2) + window.scrollY
    ])

    # Listen for save events against the dialog
    dialog.addEventListener 'save', (ev) =>
      # Add/Update/Remove the <span>
      style = ev.detail().style

      # Store the styles of the span tag (if any)
      span = @getFirstSpanTag(element, selection)
      styles = 'span { ' + span.attr('style') + ' }'
      rules = @rulesForCssText(styles)

      # Clear any existing span tag
      element.content = element.content.unformat(from, to, 'span')

      # If specified add the new style
      if style
        rules.style[@cssStyle] = style.trim()
      else
        rules.style[@cssStyle] = ""

      style = new HTMLString.Tag('span', {style: @removeSpanFromCssText(rules.cssText) })
      element.content = element.content.format(from, to, style)

      element.updateInnerHTML()
      element.taint()

      # Close the modal and dialog
      modal.unmount()
      dialog.hide()

      # Remove the fake selection from the element
      element.content = element.content.unformat(from, to, selectTag)
      element.updateInnerHTML()

      # Restore the real selection
      element.restoreState()

      # Trigger the callback
      callback(true)

    app.attach(modal)
    app.attach(dialog)
    modal.show()
    dialog.show()

  @getDialog: (element, selection) ->
    # Return the specific dialog of the style
    return new FontDialog(@getStyle(element, selection))

  @getStyle: (element, selection) ->
    # Return any existing `style` attribute for the element and selection
    span = @getFirstSpanTag(element, selection)
    if (span == false)
      return ''

    styles = 'span { ' + span.attr('style') + ' }'
    rules = @rulesForCssText(styles)
    return rules.style[@cssStyle]

  @getFirstSpanTag: (element, selection) ->
    # Find the first character in the selected text that has a `span` tag and
    # return its `font` value.
    [from, to] = selection.get()
    selectedContent = element.content.slice(from, to)
    for c in selectedContent.characters

      # Does this character have a span tag applied?
      if not c.hasTags('span')
        continue

      # Find the span tag and return the font attribute value
      for tag in c.tags()
        if tag.name() == 'span'
          return tag

      return false

  @rulesForCssText = (content) ->
    doc = document.implementation.createHTMLDocument("")
    styleElement = document.createElement("style")

    styleElement.textContent = content
    doc.body.appendChild(styleElement);

    rules = styleElement.sheet.cssRules
    return rules[0]

  @removeSpanFromCssText = (content) ->
    content = content.replace("span {", "")
    content = content.replace("}", "")
    content = content.replace(/\"/g, "'")
    console.log(content)
    return content

class ColorTool extends FontTool

  # Register the tool with the toolshelf
  ContentTools.ToolShelf.stow(@, 'color')

  # The tooltip and icon modifier CSS class for the tool
  @label = 'Tekstkleur'
  @icon = 'color'

  # The Bold provides a tagName attribute we can override to make inheriting
  # from the class cleaner.
  @tagName = 'span'
  @cssStyle = 'color'

  @getDialog: (element, selection) ->
    # Return the specific dialog of the style
    return new ColorDialog(@getStyle(element, selection))

class FontSizeTool extends FontTool

  # Register the tool with the toolshelf
  ContentTools.ToolShelf.stow(@, 'font-size')

  # The tooltip and icon modifier CSS class for the tool
  @label = 'Tekst grootte'
  @icon = 'font-size'

  # The Bold provides a tagName attribute we can override to make inheriting
  # from the class cleaner.
  @tagName = 'span'
  @cssStyle = 'fontSize'

  @getDialog: (element, selection) ->
    # Return the specific dialog of the style
    return new FontSizeDialog(@getStyle(element, selection))

class StyleDialog extends ContentTools.LinkDialog

  mount: () ->
    super()

    # Remove the new window target DOM element
    @_domElement.removeChild(@_domTargetButton)
    @_domElement.removeChild(@_domRelButton)
    @_href = false

  save: () ->
    # Save the style.
    detail = {
      style: @_domInput.value.trim()
    }
    @dispatchEvent(@createEvent('save', detail))

class FontDialog extends StyleDialog

  mount: () ->
    super()

    @_domElement.removeChild(@_domInput.parentNode)

    # Create the input element for the link
    @_domSelect = document.createElement('select')
    @_domSelect.setAttribute('class', 'ct-anchored-dialog__input')
    @_domSelect.setAttribute('name', 'font')
    @_domSelect.setAttribute('type', 'text')
    @_domElement.prepend(@_domSelect)

    options = ['Arial', 'Arial Black', 'Georgia', 'Times New Roman', 'Helvetica', 'Comic Sans MS', 'Lucida Grande', 'Tahoma', 'Courier New', 'Lucida Console',  'Verdana']

    for option in options
      @_domOption = document.createElement('option')
      @_domOption.setAttribute('value', option)
      @_domOptionContent = document.createTextNode(option)
      @_domOption.appendChild(@_domOptionContent)
      @_domSelect.appendChild(@_domOption)

    @_domInput = @_domSelect

class ColorDialog extends StyleDialog

  mount: () ->
    super()

    # Update the name and placeholder for the input field provided by the
    # link dialog.
    @_domInput.setAttribute('name', 'color')
    @_domInput.setAttribute('type', 'color')
    @_domInput.setAttribute('format', 'hex')
    @_domInput.setAttribute('placeholder', 'Selecteer de tekst kleur')

class FontSizeDialog extends StyleDialog

  mount: () ->
    super()

    # Update the name and placeholder for the input field provided by the
    # link dialog.
    @_domInput.setAttribute('name', 'font-size')
    @_domInput.setAttribute('placeholder', 'Selecteer de tekst grootte')
    @_domInput.setAttribute('type', 'number')
    @_domInput.setAttribute('min', '0')

  save: () ->
# Save the style.
    detail = {
      style: @_domInput.value.trim()+'px'
    }
    @dispatchEvent(@createEvent('save', detail))