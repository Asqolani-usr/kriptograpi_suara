import hashlib
import os
import subprocess
import binascii

def main():
    print("Starting AES-256-CBC Verification Simulation...")

    # 1. Setup Data
    original_text = b"This is a test audio file content. Verify integrity."
    password = "secret_password"

    # Key Derivation (Matches PHP: hash('sha256', $key, true))
    key_bytes = hashlib.sha256(password.encode()).digest()
    key_hex = binascii.hexlify(key_bytes).decode()

    # IV Generation (Matches PHP: openssl_random_pseudo_bytes(16))
    iv_bytes = os.urandom(16)
    iv_hex = binascii.hexlify(iv_bytes).decode()

    # Write dummy input file
    with open("test_input.dat", "wb") as f:
        f.write(original_text)

    print(f"Original Text: {original_text}")
    print(f"Derived Key (Hex): {key_hex}")
    print(f"Generated IV (Hex): {iv_hex}")

    # 2. Encrypt using OpenSSL CLI (Simulating PHP's openssl_encrypt)
    # The logic here confirms that using AES-256-CBC with a specific Key and IV works as expected.
    cmd_enc = [
        "openssl", "enc", "-aes-256-cbc",
        "-K", key_hex,
        "-iv", iv_hex,
        "-in", "test_input.dat",
        "-out", "test_encrypted.dat"
    ]

    result = subprocess.run(cmd_enc, capture_output=True)
    if result.returncode != 0:
        print("Encryption failed:", result.stderr.decode())
        return

    # Simulate PHP App Logic: Prepend IV to the file
    with open("test_encrypted.dat", "rb") as f:
        ciphertext = f.read()

    stored_file_content = iv_bytes + ciphertext
    with open("final_storage.enc", "wb") as f:
        f.write(stored_file_content)

    print("Encryption successful. File 'final_storage.enc' created (IV + Ciphertext).")

    # 3. Decrypt (Simulating PHP's decryptFile)
    print("Attempting Decryption...")

    # Read stored file
    with open("final_storage.enc", "rb") as f:
        data = f.read()

    # Extract IV (First 16 bytes)
    read_iv = data[:16]
    read_ciphertext = data[16:]
    read_iv_hex = binascii.hexlify(read_iv).decode()

    print(f"Read IV (Hex): {read_iv_hex}")

    if read_iv_hex != iv_hex:
        print("FAILURE: IV Mismatch!")
        return

    # Write temp ciphertext for openssl CLI to process
    with open("temp_cipher.dat", "wb") as f:
        f.write(read_ciphertext)

    # Decrypt using OpenSSL CLI
    cmd_dec = [
        "openssl", "enc", "-d", "-aes-256-cbc",
        "-K", key_hex,
        "-iv", read_iv_hex,
        "-in", "temp_cipher.dat",
        "-out", "test_decrypted.dat"
    ]

    result = subprocess.run(cmd_dec, capture_output=True)
    if result.returncode != 0:
        print("Decryption failed:", result.stderr.decode())
        return

    with open("test_decrypted.dat", "rb") as f:
        decrypted_text = f.read()

    print(f"Decrypted Text: {decrypted_text}")

    if decrypted_text == original_text:
        print("SUCCESS: Decrypted text matches original.")
    else:
        print("FAILURE: Content mismatch.")

    # Cleanup
    try:
        os.remove("test_input.dat")
        os.remove("test_encrypted.dat")
        os.remove("temp_cipher.dat")
        os.remove("test_decrypted.dat")
        os.remove("final_storage.enc")
    except:
        pass

if __name__ == "__main__":
    main()
