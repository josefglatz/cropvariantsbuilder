Back to [Index](Index.md) / back to [README](../../../README.md)

---

# Translation logic for cropVariant label

When you define a new cropVariant with
`\JosefGlatz\CropVariantsBuilder\CropVariant::create()` the passed
parameter is used as the cropVariant name.

The given name is also used to retrieve a meaningful label (and
translation) for the TYPO3 backend automatically.

We use the cropVariant name `md` in the following example
(`\JosefGlatz\CropVariantsBuilder\CropVariant::create('md')`).

Either the supplied XLF file is used or the XLF file in the configured extension
is used depending on your extension configuration settings of
ext:cropvariantsbuilder.

## If you set the proper extension configuration

A label
`yourconfiguredExtensionName.messages:crop_variants.md.label`
is used as final string.

> Make sure to add the proper translation string to the xlf file!

## If you do not set the proper extension configuration

A label
`cropvariantsbuilder.messages:crop_variants.md.label`
is used as final string.

> This returns the label "_Medium (md) &#8673;_". This extension ships a
> label for that.

As you can see, everytime you can not rely on the shipped translation strings
of ext:cropvariants!

## Overwrite label with a custom content

If you do not want to use XLF files for translating labels at all or you want
to set a custom `whateverforthislabel...` label then you can use the
method `setTitle()` of the CropVariant class.


---

# Available defaults

Please see
[shipped locallang.xlf](/Resources/Private/Language/locallang.xlf) for
details. If you need more common labels, please provide a [pull request](https://github.com/josefglatz/cropvariantsbuilder/pulls) or
[create an issue](https://github.com/josefglatz/cropvariantsbuilder/issues/new)!
