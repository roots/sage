var alphabet = 'a!`=[]\\;\':"/<> &';

var out             = document.getElementById('out');
var testContainer   = document.getElementById('testContainer');

function print(s) {
    out.value += s + "\n";
}

function testImage() {
    return testContainer.firstChild;
}

function test(input) {
    var count = 0;
    var oldInput, newInput;
    testContainer.innerHTML = "<img />";
    testImage().setAttribute("alt", input);
    print("------");
    print("Test input: " + input);
    do {
        oldInput = testImage().getAttribute("alt");
        var intermediate = testContainer.innerHTML;
        print("Render: " + intermediate);
        testContainer.innerHTML = intermediate;
        if (testImage() == null) {
            print("Image disappeared...");
            break;
        }
        newInput = testImage().getAttribute("alt");
        print("New value: " + newInput);
        count++;
    } while (count < 5 && newInput != oldInput);
    if (count == 5) {
        print("Failed to achieve fixpoint");
    }
    testContainer.innerHTML = "";
}

print("Go!");

test("`` ");
test("'' ");

for (var i = 0; i < alphabet.length; i++) {
    for (var j = 0; j < alphabet.length; j++) {
        test(alphabet.charAt(i) + alphabet.charAt(j));
    }
}

// document.getElementById('out').textContent = alphabet;
