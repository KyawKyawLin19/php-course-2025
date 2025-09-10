# Complete Markdown Structure Guide

## Headers
```
# H1 - Main Title (largest)
## H2 - Section Header
### H3 - Subsection
#### H4 - Sub-subsection
##### H5 - Minor heading
###### H6 - Smallest heading
```

## Text Formatting
```
**Bold text** or __Bold text__
*Italic text* or _Italic text_
***Bold and italic*** or ___Bold and italic___
~~Strikethrough text~~
<u>Underlined text</u>
`Inline code`
```

## Lists

### Unordered Lists
```
- Item 1
- Item 2
  - Sub item 2.1
  - Sub item 2.2
- Item 3

* Alternative bullet
+ Another alternative
```

### Ordered Lists
```
1. First item
2. Second item
   1. Sub item 2.1
   2. Sub item 2.2
3. Third item
```

### Task Lists
```
- [x] Completed task
- [ ] Incomplete task
- [ ] Another task
```

## Links and Images
```
[Link text](https://example.com)
[Link with title](https://example.com "Title text")

![Alt text](image.jpg)
![Alt text](image.jpg "Image title")
```

## Code Blocks

### Inline Code
```
Use `backticks` for inline code
```

### Code Blocks
````
```
Basic code block
```

```python
# Python code with syntax highlighting
def hello():
    print("Hello World")
```

```javascript
// JavaScript example
function greet() {
    console.log("Hello!");
}
```
````

## Tables
```
| Header 1 | Header 2 | Header 3 |
|----------|----------|----------|
| Row 1    | Data     | Data     |
| Row 2    | Data     | Data     |

| Left | Center | Right |
|:-----|:------:|------:|
| Left | Center | Right |
```

## Quotes
```
> Single line quote

> Multi-line quote
> continues here
> and here

> Nested quotes
>> Second level
>>> Third level
```

## Horizontal Rules
```
---
***
___
```

## Line Breaks
```
Two spaces at end of line  
creates line break

Double enter

creates paragraph break
```

## Escape Characters
```
\* Escaped asterisk
\# Escaped hash
\` Escaped backtick
\\ Escaped backslash
```

## HTML in Markdown
```html
<strong>Bold HTML</strong>
<em>Italic HTML</em>
<u>Underlined</u>
<mark>Highlighted</mark>
<sub>Subscript</sub>
<sup>Superscript</sup>
<br> Line break
```

## Advanced Features

### Footnotes
```
Here's a sentence with a footnote[^1].

[^1]: This is the footnote text.
```

### Definition Lists
```
Term 1
: Definition 1

Term 2
: Definition 2a
: Definition 2b
```

### Abbreviations
```
*[HTML]: Hyper Text Markup Language
The HTML specification is maintained by W3C.
```

## Mathematical Expressions (if supported)
```
Inline math: $x = y^2$
Block math:
$$
x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}
$$
```

## Comments (not displayed)
```
<!-- This is a comment -->
[//]: # (This is also a comment)
```

## Example Complete Structure
```markdown
# Main Document Title

## Introduction
This is a **bold** introduction with *italic* text.

### Features List
- Feature 1
- Feature 2
  - Sub-feature 2.1
  - Sub-feature 2.2

### Code Example
```python
def example():
    return "Hello World"
```

### Data Table
| Name | Age | City |
|------|-----|------|
| John | 25  | NYC  |
| Jane | 30  | LA   |

## Conclusion
> This is a quote to conclude the document.

---
*Footer text*
```