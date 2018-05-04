class FontTool extends ContentTools.Tools.Bold

  # Insert/Remove a <time> tag.

  # Register the tool with the toolshelf
  ContentTools.ToolShelf.stow(@, 'font')

  # The tooltip and icon modifier CSS class for the tool
  @label = 'Font'
  @icon = 'font'

  # The Bold provides a tagName attribute we can override to make inheriting
  # from the class cleaner.
  @tagName = 'span'

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
    dialog = new FontDialog(@getFont(element, selection))
    dialog.position([
      rect.left + (rect.width / 2) + window.scrollX,
      rect.top + (rect.height / 2) + window.scrollY
    ])

    # Listen for save events against the dialog
    dialog.addEventListener 'save', (ev) =>
      # Add/Update/Remove the <span>
      font = ev.detail().font

      # Store the styles of the span tag (if any)
      span = @getFirstSpanTag(element, selection)
      styles = 'span { ' + span.attr('style') + ' }'
      rules = @rulesForCssText(styles)

      # Clear any existing span tag
      element.content = element.content.unformat(from, to, 'span')

      # If specified add the new font
      if font
        rules.style.fontFamily = font
        font = new HTMLString.Tag('span', {style: @removeSpanFromCssText(rules.cssText) })
        element.content = element.content.format(from, to, font)

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

  @getFont: (element, selection) ->
    # Return any existing `font` attribute for the element and selection

    span = @getFirstSpanTag(element, selection)
    if (span == false)
      return ''

    styles = 'span { ' + span.attr('style') + ' }'
    rules = @rulesForCssText(styles)
    return rules.style.fontFamily

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
    return content

class FontDialog extends ContentTools.LinkDialog

  # An anchored dialog to support inserting/modifying a <span> tag

  mount: () ->
    super()

    # Update the name and placeholder for the input field provided by the
    # link dialog.
    @_domInput.setAttribute('name', 'font')
    @_domInput.setAttribute('placeholder', 'Select your font')

    # Remove the new window target DOM element
    @_domElement.removeChild(@_domTargetButton)
    @_domElement.removeChild(@_domRelButton)

  save: () ->
    # Save the font.
    detail = {
      font: @_domInput.value.trim()
    }
    @dispatchEvent(@createEvent('save', detail))