#define SASS_BASE64VLQ

#include <string>

namespace Sass {
  using std::string;

  class Base64VLQ {

  public:

    string encode(const int number) const;

  private:

    char base64_encode(const int number) const;

    int to_vlq_signed(const int number) const;

    static const char* CHARACTERS;

    static const int VLQ_BASE_SHIFT;
    static const int VLQ_BASE;
    static const int VLQ_BASE_MASK;
    static const int VLQ_CONTINUATION_BIT;
  };

}
