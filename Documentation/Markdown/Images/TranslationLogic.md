Back to [Index](Index.md) / back to [README](../../../README.md)

---

# Translation logic for cropVariant label

When you define a new cropVariant with
`\JosefGlatz\CropVariantsBuilder\CropVariant::create()` the passed
parameter is used as the cropVariant name.

The given name is also used to retrieve a meaningful label (and
translation) for the TYPO3 backend automatically.

We use the cropVariant name `md` in the following example.

## 1. An existing label is searched for in the extension

A label
`LLL:EXT:cropvariantsbuilder/Resources/Private/Language/locallang.xlf:crop_variants.md.label`
is being tested.

> This returns the label "_Medium (md) &#8673;_". This extension ships a
> label for that.

## 2. An existing label is search for in your configured provider extension (it checks whether the default translate has been overwritten in your configured configuration provider extension)

A label
`LLL:EXT:my_nice_site_extension/Resources/Private/Language/locallang.xlf:crop_variants.md.label`
is being tested.
[Your extension and xlf file name can be configured](../../../README.md#configuration).

> In this example the label isn't set in the configuration provider
> extension. As a result, the standard translation of
> `ext:cropvariantsbuilder` is used.

---

# Available defaults

Please see [shipped locallang.xlf](../../../Resources/Private/Language/locallang.xlf) for details. If you need more common labels, please provide a [pull request](https://github.com/josefglatz/cropvariantsbuilder/pulls) or [create an issue](https://github.com/josefglatz/cropvariantsbuilder/issues/new)!
